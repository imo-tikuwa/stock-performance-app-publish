<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use App\Form\SearchForm;
use Cake\Event\EventInterface;
use Cake\I18n\FrozenDate;
use DateTime;
use DateTimeZone;

/**
 * DailyRecords Controller
 *
 * @property \App\Model\Table\DailyRecordsTable $DailyRecords
 * @property \App\Model\Table\AccountsTable $Accounts
 *
 * @method \App\Model\Entity\DailyRecord[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DailyRecordsController extends AppController
{
    /**
     *
     * {@inheritDoc}
     * @see \App\Controller\Admin\AppController::beforeFilter()
     */
    public function beforeFilter(EventInterface $event)
    {
        $result = parent::beforeFilter($event);
        if (!is_null($result) && $result instanceof \Cake\Http\Response) {
            return $result;
        }

        $this->loadModel('Accounts');

        if (in_array($this->getRequest()->getParam('action'), [ACTION_INDEX, ACTION_ADD, ACTION_EDIT], true)) {
            $account_id_list = $this->Accounts->find('list', ['keyField' => 'id', 'valueField' => 'name'])->toArray();
            if (empty($account_id_list)) {
                $this->Flash->error('口座が登録されていません。');
                return $this->redirect(['controller' => 'Top', 'action' => 'index']);
            }

            $this->set(compact('account_id_list'));
        }
    }

    /**
     * Paging setting.
     */
    public $paginate = [
        'limit' => 20,
    ];

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $request = $this->getRequest()->getQueryParams();
        $query = $this->DailyRecords->getSearchQuery($request);
        $daily_records = $this->paginate($query);
        $search_form = new SearchForm();
        $search_form->setData($request);

        $this->set(compact('daily_records', 'search_form'));
    }

    /**
     * View method
     *
     * @param string|null $id Daily Record id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $daily_record = $this->DailyRecords->get($id, [
            'contain' => [
                'Accounts',
            ]
        ]);

        $this->set('daily_record', $daily_record);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null
     */
    public function add()
    {
        return $this->_form();
    }

    /**
     * Edit method
     *
     * @param string|null $id 資産記録ID
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        return $this->_form($id);
    }

    /**
     * Add and Edit Common method
     *
     * @param string|null $id 資産記録ID
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    private function _form($id = null)
    {
        if ($this->getRequest()->getParam('action') == 'edit') {
            $daily_record = $this->DailyRecords->get($id, [
                'contain' => [
                    'Accounts',
                ]
            ]);
            $this->DailyRecords->touch($daily_record);
        } else {
            $daily_record = $this->DailyRecords->newEmptyEntity();
        }

        // 分析画面から遷移してきたとき初期表示として口座と日付をセット
        if ($this->getRequest()->is(['get']) && $this->getRequest()->getParam('action') == 'add' && !is_null($this->getRequest()->getQuery('account_id')) && !is_null($this->getRequest()->getQuery('day'))) {
            $daily_record->account_id = $this->getRequest()->getQuery('account_id');
            $daily_record->day = FrozenDate::parse($this->getRequest()->getQuery('day'));
        }

        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $daily_record = $this->DailyRecords->patchEntity($daily_record, $this->getRequest()->getData(), [
                'associated' => [
                    'Accounts',
                ]
            ]);
            if ($daily_record->hasErrors()) {
                $this->Flash->set(implode('<br />', $daily_record->getErrorMessages()), [
                    'escape' => false,
                    'element' => 'validation_error',
                    'params' => ['alert-class' => 'text-sm']
                ]);
            } else {
                $conn = $this->DailyRecords->getConnection();
                $conn->begin();
                if ($this->DailyRecords->save($daily_record, ['atomic' => false])) {
                    $conn->commit();
                    $this->Flash->success('資産記録の登録が完了しました。');

                    return $this->redirect(['action' => 'index', '?' => _code('InitialOrders.DailyRecords')]);
                }
                $conn->rollback();
            }
        }
        $this->set(compact('daily_record'));
        $this->render('edit');
    }

    /**
     * Delete method
     *
     * @param string|null $id 資産記録ID
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     * @throws \Cake\Http\Exception\MethodNotAllowedException When request method invalid.
     */
    public function delete($id = null)
    {
        $this->getRequest()->allowMethod('delete');
        $entity = $this->DailyRecords->get($id);
        if ($this->DailyRecords->delete($entity)) {
            $this->Flash->success('資産記録の削除が完了しました。');
        } else {
            $this->Flash->error('エラーが発生しました。');
        }

        return $this->redirect(['action' => 'index', '?' => _code('InitialOrders.DailyRecords')]);
    }

    /**
     * csv export method
     * @return void
     */
    public function csvExport()
    {
        $request = $this->getRequest()->getQueryParams();
        $daily_records = $this->DailyRecords->getSearchQuery($request)->toArray();
        $extract = [
            // ID
            'id',
            // 口座名
            function ($row) {
                return @$row['account']['name'];
            },
            // 日付
            function ($row) {
                return $row['day']?->i18nFormat('yyyy-MM-dd');
            },
            // 資産額
            function ($row) {
                return !is_null($row['record']) ? "{$row['record']}円" : null;
            },
            // 作成日時
            function ($row) {
                return $row['created']?->i18nFormat('yyyy-MM-dd HH:mm:ss');
            },
            // 更新日時
            function ($row) {
                return $row['modified']?->i18nFormat('yyyy-MM-dd HH:mm:ss');
            },
        ];

        $datetime = (new DateTime('now', new DateTimeZone('Asia/Tokyo')))->format('YmdHis');
        $this->response = $this->response->withDownload("daily_records-{$datetime}.csv");
        $this->viewBuilder()->setClassName('CsvView.Csv');
        $this->viewBuilder()->setOptions([
            'serialize' => 'daily_records',
            'header' => $this->DailyRecords->getCsvHeaders(),
            'extract' => $extract,
            'csvEncoding' => 'UTF-8'
        ]);
        $this->set(compact('daily_records'));
    }
}
