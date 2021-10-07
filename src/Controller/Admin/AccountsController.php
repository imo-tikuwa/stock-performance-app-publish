<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use App\Form\SearchForm;
use DateTime;
use DateTimeZone;

/**
 * Accounts Controller
 *
 * @property \App\Model\Table\AccountsTable $Accounts
 *
 * @method \App\Model\Entity\Account[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AccountsController extends AppController
{
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
        $query = $this->Accounts->getSearchQuery($request);
        $accounts = $this->paginate($query);
        $search_form = new SearchForm();
        $search_form->setData($request);

        $this->set(compact('accounts', 'search_form'));
    }

    /**
     * View method
     *
     * @param string|null $id Account id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $account = $this->Accounts->get($id);

        $this->set('account', $account);
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
     * @param string|null $id 口座ID
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
     * @param string|null $id 口座ID
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    private function _form($id = null)
    {
        if ($this->getRequest()->getParam('action') == 'edit') {
            $account = $this->Accounts->get($id, [
                'contain' => [
                    'DailyRecords',
                ]
            ]);
            $this->Accounts->touch($account);
        } else {
            $account = $this->Accounts->newEmptyEntity();
        }
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $account = $this->Accounts->patchEntity($account, $this->getRequest()->getData(), [
                'associated' => [
                    'DailyRecords',
                ]
            ]);
            if ($account->hasErrors()) {
                $this->Flash->set(implode('<br />', $account->getErrorMessages()), [
                    'escape' => false,
                    'element' => 'validation_error',
                    'params' => ['alert-class' => 'text-sm']
                ]);
            } else {
                $conn = $this->Accounts->getConnection();
                $conn->begin();
                if ($this->Accounts->save($account, ['atomic' => false])) {
                    $conn->commit();
                    $this->Flash->success('口座の登録が完了しました。');

                    return $this->redirect(['action' => 'index', '?' => _code('InitialOrders.Accounts')]);
                }
                $conn->rollback();
            }
        }
        $this->set(compact('account'));
        $this->render('edit');
    }

    /**
     * csv export method
     * @return void
     */
    public function csvExport()
    {
        $request = $this->getRequest()->getQueryParams();
        $accounts = $this->Accounts->getSearchQuery($request)->toArray();
        $extract = [
            // ID
            'id',
            // 口座名
            'name',
            // 初期資産額
            function ($row) {
                return !is_null($row['init_record']) ? "{$row['init_record']}円" : null;
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
        $this->response = $this->response->withDownload("accounts-{$datetime}.csv");
        $this->viewBuilder()->setClassName('CsvView.Csv');
        $this->viewBuilder()->setOptions([
            'serialize' => 'accounts',
            'header' => $this->Accounts->getCsvHeaders(),
            'extract' => $extract,
            'csvEncoding' => 'UTF-8'
        ]);
        $this->set(compact('accounts'));
    }
}
