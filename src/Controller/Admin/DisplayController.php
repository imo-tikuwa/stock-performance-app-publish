<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use App\Form\SearchForm;
use App\Utils\RecordUtils;
use Cake\Datasource\ConnectionManager;
use Cake\Event\EventInterface;
use Cake\Utility\Hash;
use DateInterval;
use DateTime;

/**
 * DailyRecords Controller
 *
 * @property \App\Model\Table\AccountsTable $Accounts
 * @property \App\Model\Table\CalendarsTable $Calendars
 * @property \App\Model\Table\ConfigsTable $Configs
 * @property \App\Model\Table\DailyRecordsTable $DailyRecords
 * @property \App\Model\Entity\Config $config
 *
 */
class DisplayController extends AppController
{
    /**
     *
     * {@inheritDoc}
     * @see \App\Controller\Admin\AppController::beforeFilter()
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        // 設定取得
        $this->loadModel('Configs');
        $this->config = $this->Configs->get(1);
        $this->set('config', $this->config);
    }

    /**
     * 設定
     */
    public $config;

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->viewBuilder()->addHelper('Display');
        $this->loadModel('Accounts');
        $this->loadModel('Calendars');
        $this->loadModel('DailyRecords');
        $request = $this->getRequest()->getQueryParams();

        // 月ごとモードかどうかで画面表示するdatepickerを切り替える
        if ($this->config->display_only_month === DISPLAY_ONLY_MONTH_ON) {
            if (!isset($request['month'])) {
                $request['month'] = (new DateTime('first day of this month'))->format('Y-m');
            }
            $day_from = "{$request['month']}-01";
            $day_to = (new DateTime("last day of {$request['month']}"))->format('Y-m-d');
        } else {
            // 日付の初期値は現在の月初～月末(未設定不可)
            if (!isset($request['day_from'])) {
                $request['day_from'] = (new DateTime('first day of this month'))->format('Y-m-d');
            }
            $day_from = $request['day_from'];
            if (!isset($request['day_to'])) {
                $request['day_to'] = (new DateTime('last day of this month'))->format('Y-m-d');
            }
            $day_to = $request['day_to'];
        }

        // 単位表示の初期値はON
        if (!isset($request['display_unit'])) {
            $request['display_unit'] = _code('Codes.Display.display_unit_on');
        }

        // データ取得＆画面表示用のデータ作成
        $display_data = $this->createDisplayData($request);

        $search_form = new SearchForm();
        $search_form->setData($request);

        $this->set(compact('display_data', 'search_form'));
    }

    /**
     * 画面表示で使用するデータを作成する
     * @param array $request リクエスト情報
     * @return array
     */
    private function createDisplayData($request)
    {
        // 単位表示する
        $display_unit = isset($request['display_unit']) && _code('Codes.Display.display_unit_on') === $request['display_unit'];
        // 入出金を含める
        $include_deposit = isset($request['include_deposit']) && _code('Codes.Display.include_deposit_on') === $request['include_deposit'];

        // 資産情報を取得
        $daily_records = $this->DailyRecords->find()->where([
            $this->DailyRecords->aliasField('day >=') => $request['day_from'],
            $this->DailyRecords->aliasField('day <=') => $request['day_to'],
        ])
        ->group($this->DailyRecords->aliasField('id'))
        ->toArray();

        $business_days = $this->Calendars->findDisplayTargetDates($request['day_from'], $request['day_to']);
        $accounts = $this->Accounts->find('list', ['keyField' => 'id', 'valueField' => 'name'])->toArray();

        // 口座情報が存在しないときエラー
        if (empty($accounts)) {
            $this->Flash->error('口座が登録されていません。');
            return $this->redirect(['controller' => 'Top', 'action' => 'index']);
        }

        // 設定取得
        $display_data = [
            'accounts' => $accounts,
            'records' => [],
            'chartData' => '',
            'display_only_month' => ($this->config->display_only_month === DISPLAY_ONLY_MONTH_ON),
        ];

        // 口座ごとに日付辺りの金額を持った配列を作成
        foreach ($accounts as $account_id => $account_name) {
            /** @var \App\Model\Entity\DailyRecord[] $account_daily_records */
            $account_daily_records = Hash::extract($daily_records, "{n}[account_id={$account_id}]");
            $start_datetime = new DateTime($request['day_from']);
            $end_datetime = new DateTime($request['day_to']);

            while ((int)($start_datetime->diff($end_datetime)->format('%R%a')) >= 0) {
                $current_ymd = $start_datetime->format('Y-m-d');
                if (in_array($current_ymd, $business_days)) {
                    $daily_record_id = null;
                    $day_record = null;
                    foreach ($account_daily_records as $daily_record) {
                        if ($daily_record->day->i18nFormat('yyyy-MM-dd') === $current_ymd) {
                            $day_record = $daily_record->record;
                            $daily_record_id = $daily_record->id;
                            break;
                        }
                    }
                    $display_data['records'][$current_ymd]["account{$account_id}_daily_record"] = $day_record;
                    $display_data['records'][$current_ymd]["account{$account_id}_daily_record_id"] = $daily_record_id;
                    // record_total = 証券口座合計
                    if (!array_key_exists('record_total', $display_data['records'][$current_ymd])) {
                        $display_data['records'][$current_ymd]['record_total'] = null;
                    }
                    if (!is_null($day_record)) {
                        if (is_null($display_data['records'][$current_ymd]['record_total'])) {
                            $display_data['records'][$current_ymd]['record_total'] = 0;
                        }
                        $display_data['records'][$current_ymd]['record_total'] += $day_record;
                    }
                }
                $start_datetime->add(new DateInterval('P1D'));
            }
        }

        // レコード情報が存在しないときエラー
        if (empty($display_data['records'])) {
            $this->Flash->error('分析画面で資産情報が取得できませんでした。');
            return $this->redirect(['controller' => 'Top', 'action' => 'index']);
        }

        // 実質資産額(record_total_real)と入出金を計算
        // 現在の資産記録から入金データ分を除外した金額を計算する
        // まず、入金テーブルから日付ごとの入金総額を取得
        $connection = ConnectionManager::get('default');
        $sql = <<<EOS
select
    calendars.day,
    deposits.id as deposit_id,
    deposits.deposit_amount,
    ifnull((
            select
                sum(deposits.deposit_amount)
            from
                deposits
            where
                deposits.deposit_date <= calendars.day
        ), 0) as deposit_sum
from
    calendars
    left join
        deposits
    on  deposits.deposit_date = calendars.day
where
    calendars.is_holiday = 0
order by
    calendars.day asc
EOS;
        $deposits = $connection->execute($sql)->fetchAll('assoc');
        $deposits = Hash::combine($deposits, '{n}.day', '{n}');
        // 上のwhileループ内で作成した証券口座合計(record_total)に入っている金額に同一日付の入金総額を減算することで実質資産を作成
        foreach ($display_data['records'] as $date => $record) {
            // 実質資産
            $display_data['records'][$date]['record_total_real'] = !is_null($record['record_total']) ? $record['record_total'] - $deposits[$date]['deposit_sum'] : null;
            // 入出金
            $display_data['records'][$date]['deposit_id'] = $deposits[$date]['deposit_id'];
            $display_data['records'][$date]['deposit_day_ammount'] = $deposits[$date]['deposit_amount'];
        }

        // 作成した日ごと口座ごとのデータを元に分析データを作成
        // 初回のループ用に、前回の資産額を知る必要があるためここで取得する。
        // 見つからない場合は各口座の初期資産額を合算する
        $first_day = array_key_first($display_data['records']);
        $last_record_total_real = RecordUtils::getLastRecordTotalReal($first_day);
        $month_last_record_total_real = RecordUtils::getMonthLastRecordTotalReal($first_day);
        $year_first_record_total_real = RecordUtils::getYearFirstRecordTotalReal($first_day);
        foreach ($display_data['records'] as $date => $record) {
            // 月・年が変わったら計算用の前月・前年最終営業日の実質資産額を取得しなおす
            if ($first_day != $date && str_ends_with($date, '-01')) {
                $month_last_record_total_real = RecordUtils::getMonthLastRecordTotalReal($date);
            }
            if ($first_day != $date && str_ends_with($date, '-01-01')) {
                $year_first_record_total_real = RecordUtils::getYearFirstRecordTotalReal($date);
            }

            // 初期値
            // 証券口座合計がnull = その日付のデータは登録されていないため0ではなくnullで設定して次ループへ
            if (is_null($record['record_total'])) {
                $display_data['records'][$date] += [
                    'prev_day_diff_value' => null,
                    'prev_day_diff_rate' => null,
                    'prev_month_diff_value' => null,
                    'prev_month_diff_rate' => null,
                    'beginning_year_diff_value' => null,
                    'beginning_year_diff_rate' => null,
                ];
                $last_record_total_real = $record['record_total_real'];
                continue;
            }
            $display_data['records'][$date] += [
                'prev_day_diff_value' => 0,
                'prev_day_diff_rate' => 0,
                'prev_month_diff_value' => 0,
                'prev_month_diff_rate' => 0,
                'beginning_year_diff_value' => 0,
                'beginning_year_diff_rate' => 0,
            ];

            if (!is_null($record['record_total_real']) && !is_null($last_record_total_real)) {
                // 前営業日比
                $display_data['records'][$date]['prev_day_diff_value'] = $record['record_total_real'] - $last_record_total_real;
                // 前営業日比(%)
                $display_data['records'][$date]['prev_day_diff_rate'] = round(($record['record_total_real'] - $last_record_total_real) / $last_record_total_real * 100, 1);
            }
            if (!is_null($record['record_total_real'])) {
                if (!is_null($month_last_record_total_real)) {
                    // 単月成績
                    $display_data['records'][$date]['prev_month_diff_value'] = $record['record_total_real'] - $month_last_record_total_real;
                    // 単月成績(%)
                    $display_data['records'][$date]['prev_month_diff_rate'] = round(($record['record_total_real'] - $month_last_record_total_real) / $month_last_record_total_real * 100, 1);
                }
                if (!is_null($year_first_record_total_real)) {
                    // 年初来成績
                    $display_data['records'][$date]['beginning_year_diff_value'] = $record['record_total_real'] - $year_first_record_total_real;
                    // 年初来成績(%)
                    $display_data['records'][$date]['beginning_year_diff_rate'] = round(($record['record_total_real'] - $year_first_record_total_real) / $year_first_record_total_real * 100, 1);
                }
            }
            $last_record_total_real = $record['record_total_real'];
        }

        // chart.js用の配列を作成
        // 日付のフォーマットは年月日だと横長なので月日にする
        // X軸についてTimeScaleで処理すると休業日の分だけポイント間が開いてしまうため文字列として処理する
        $dates = array_keys(Hash::get($display_data, 'records'));
        $dates = array_map(function ($date) {
            return (new DateTime($date))->format('n/j');
        }, $dates);

        // 資産額の最大値の桁数に応じて単位テキストの作成およびチャート用の除算を行う
        $amount_path = $include_deposit ? 'records.{s}.record_total' : 'records.{s}.record_total_real';
        $amount_values = Hash::extract($display_data, $amount_path);
        $max_amount_digits = strlen((string)max($amount_values));
        $y_unit = '';
        if ($display_unit) {
            switch ($max_amount_digits) {
                case 7:
                    $y_unit = '(百万円)';
                    break;
                case 6:
                    $y_unit = '(十万円)';
                    break;
                case 5:
                    $y_unit = '(万円)';
                    break;
                default:
                    $y_unit = '(円)';
            }
        }
        $devide_num = (int)str_pad('1', $max_amount_digits, '0');
        $amount_values = array_map(function ($amount_value) use ($devide_num) {
            return !is_null($amount_value) ? $amount_value / $devide_num : null;
        }, $amount_values);

        // chart.js用の配列組み立て
        $display_data['chartData'] = [
            'type' => 'line',
            'data' => [
                'labels' => $dates,
                'datasets' => [
                    [
                        'label' => "実質資産{$y_unit}",
                        'backgroundColor' => "#{$this->config->record_total_real_color}",
                        'borderColor' => "#{$this->config->record_total_real_color}",
                        'lineTension' => 0.2,
                        'data' => $amount_values,
                    ],
                ],
            ],
            'options' => [
                'interaction' => [
                    'mode' => 'index',
                    'intersect' => false,
                ],
                'layout' => [
                    'padding' => [
                        'top' => 10,
                        'left' => 30,
                        'bottom' => 10,
                        'right' => 10,
                    ],
                ],
                'scales' => [
                    'y' => [
                        'position' => 'right',
                    ],
                    'x' => [
                        'ticks' => [
                            'maxRotation' => 0,
                        ],
                    ],
                ],
                'animation' => [
                    'duration' => 0,
                ],
                'plugins' => [
                    'legend' => [
                        'position' => 'bottom',
                        'align' => 'end'
                    ],
                    'title' => [
                        'display' => true,
                        'text' => 'StockPerformance',
                        'align' => 'start',
                        'position' => 'top',
                        'padding' => 0,
                        'font' => [
                            'size' => 18,
                            'family' => '游ゴシック体,YuGothic,游ゴシック Medium,Yu Gothic Medium,游ゴシック,Yu Gothic,sans-serif',
                            'lineHeight' => 1,
                            'style' => 'italic',
                        ],
                        'color' => '#999999'
                    ]
                ],
                'elements' => [
                    'point' => [
                        'pointStyle' => 'circle',
                        'borderWidth' => 2,
                        'hoverRadius' => 7,
                    ],
                    'line' => [
                        'borderWidth' => 4
                    ],
                ]
            ],
        ];

        // 単位表示がOFFのとき単位をY軸の単位を非表示
        if (!$display_unit) {
            $display_data['chartData']['options']['scales']['y'] = array_merge($display_data['chartData']['options']['scales']['y'], [
                'ticks' => [
                    'display' => false,
                ]
            ]);
        }

        // 設定で初期資産表示がONとき初期資産をチャートに表示する
        if ($this->config->display_init_record === DISPLAY_INIT_RECORD_ON) {
            $disp_init_record = $this->Accounts->find()->sumOf('init_record') / $devide_num;
            $display_data['chartData']['data']['datasets'][] = [
                'label' => "初期資産{$y_unit}",
                'backgroundColor' => "#{$this->config->init_record_color}",
                'borderColor' => "#{$this->config->init_record_color}",
                'lineTension' => 0.2,
                'pointRadius' => 0,
                'data' => array_fill(0, count($dates), $disp_init_record),
            ];
        }

        return $display_data;
    }
}
