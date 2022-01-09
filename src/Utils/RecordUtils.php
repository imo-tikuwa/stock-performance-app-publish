<?php
namespace App\Utils;

use Cake\ORM\TableRegistry;
use DateTime;

/**
 * 分析周りで使用する関数をまとめたUtilクラス
 */
class RecordUtils
{
    /**
     * 前営業日の実質資産額を取得する
     * @param string $date_str 日付文字列
     * @return int|null 前営業日の実質資産額
     */
    public static function getLastRecordTotalReal($date_str)
    {
        // カレンダーから前営業日を取得
        /** @var \App\Model\Table\CalendarsTable $calendars_table */
        $calendars_table = TableRegistry::getTableLocator()->get('Calendars');
        /** @var \App\Model\Entity\Calendar|null $init_calendar */
        $init_calendar = $calendars_table->find()
        ->where(['day <' => $date_str, 'is_holiday' => false])
        ->order(['day' => 'desc'])
        ->first();
        $last_business_day = $init_calendar?->day?->i18nFormat('yyyy-MM-dd');
        if (is_null($last_business_day)) {
            return null;
        }
        assert(is_string($last_business_day));

        // 前営業日が存在する場合は、その日の資産総額と入金総額から実質資産を取得
        return self::calcRecordTotalReal($last_business_day);
    }

    /**
     * 前月最終営業日の実質資産額を取得する
     * @param string $date_str 日付文字列
     * @return int|null 前営業日の実質資産額
     */
    public static function getMonthLastRecordTotalReal($date_str)
    {
        // 前月の最終日
        $last_month_last_day = (new DateTime($date_str))->modify('last day of last month');
        $last_month_last_day_str = $last_month_last_day->format('Y-m-d');

        // カレンダーから前月の最終営業日を取得
        /** @var \App\Model\Table\CalendarsTable $calendars_table */
        $calendars_table = TableRegistry::getTableLocator()->get('Calendars');
        /** @var \App\Model\Entity\Calendar|null $init_calendar */
        $init_calendar = $calendars_table->find()
        ->where(['day <=' => $last_month_last_day_str, 'is_holiday' => false])
        ->order(['day' => 'desc'])
        ->first();
        $last_business_day = $init_calendar?->day?->i18nFormat('yyyy-MM-dd');
        if (is_null($last_business_day)) {
            // 見つからない場合は当月の最初の営業日で計算する
            $current_month_first_day_str = $last_month_last_day->modify('+1 days')->format('Y-m-d');
            /** @var \App\Model\Entity\Calendar|null $init_calendar */
            $init_calendar = $calendars_table->find()
            ->where(['day >=' => $current_month_first_day_str, 'is_holiday' => false])
            ->order(['day' => 'asc'])
            ->first();
            $last_business_day = $init_calendar?->day?->i18nFormat('yyyy-MM-dd');

            // それでも見つからなかったら表示不可能っぽいのでnull返す
            if (is_null($last_business_day)) {
                return null;
            }
        }
        assert(is_string($last_business_day));

        // 前月の最終営業日が存在する場合は、その日の資産総額と入金総額から実質資産を取得
        return self::calcRecordTotalReal($last_business_day);
    }

    /**
     * 前年最終営業日の実質資産額を取得する
     * @param string $date_str 日付文字列
     * @return int|null 前営業日の実質資産額
     */
    public static function getYearFirstRecordTotalReal($date_str)
    {
        // 今年の年初
        $current_year_first_day_str = (new DateTime($date_str))->format('Y-01-01');

        // カレンダーから年初の営業日を取得
        /** @var \App\Model\Table\CalendarsTable $calendars_table */
        $calendars_table = TableRegistry::getTableLocator()->get('Calendars');
        /** @var \App\Model\Entity\Calendar|null $init_calendar */
        $init_calendar = $calendars_table->find()
        ->where(['day >=' => $current_year_first_day_str, 'is_holiday' => false])
        ->order(['day' => 'asc'])
        ->first();
        $year_first_business_day = $init_calendar?->day?->i18nFormat('yyyy-MM-dd');
        if (is_null($year_first_business_day)) {
            return null;
        }
        assert(is_string($year_first_business_day));

        // 年初の営業日が存在する場合は、その日の資産総額と入金総額から実質資産を取得
        return self::calcRecordTotalReal($year_first_business_day);
    }

    /**
     * 引数の営業日時点の資産総額と入金総額取得し実質資産を返す
     * @param string $business_day 営業日付
     * @return int|null 実質資産
     */
    private static function calcRecordTotalReal($business_day)
    {
        /** @var \App\Model\Table\DailyRecordsTable $daily_records_table */
        $daily_records_table = TableRegistry::getTableLocator()->get('DailyRecords');
        $query = $daily_records_table->find()->where(['day' => $business_day]);
        if ($query->count() <= 0) {
            return null;
        }
        $daily_record_sum = $query->sumOf('record');

        /** @var \App\Model\Table\DepositsTable $deposits_table */
        $deposits_table = TableRegistry::getTableLocator()->get('Deposits');
        $target_date_deposit_sum = $deposits_table->find()
        ->where(['deposit_date <=' => $business_day])
        ->sumOf('deposit_amount');
        $daily_record_sum -= $target_date_deposit_sum;

        return (int)$daily_record_sum;
    }
}
