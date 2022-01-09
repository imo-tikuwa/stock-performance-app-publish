<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use App\Form\SearchForm;
use Cake\I18n\FrozenDate;
use DateTime;
use DateTimeZone;

/**
 * Deposits Controller
 *
 * @property \App\Model\Table\DepositsTable $Deposits
 *
 * @method \App\Model\Entity\Deposit[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DepositsController extends AppController
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
        $query = $this->Deposits->getSearchQuery($request);
        $deposits = $this->paginate($query);
        $search_form = new SearchForm();
        $search_form->setData($request);

        $this->set(compact('deposits', 'search_form'));
    }

    /**
     * View method
     *
     * @param string|null $id Deposit id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $deposit = $this->Deposits->get($id);

        $this->set('deposit', $deposit);
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
     * @param string|null $id 入出金ID
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
     * @param string|null $id 入出金ID
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    private function _form($id = null)
    {
        if ($this->getRequest()->getParam('action') == 'edit') {
            $deposit = $this->Deposits->get($id);
            $this->Deposits->touch($deposit);
        } else {
            $deposit = $this->Deposits->newEmptyEntity();
        }

        // 分析画面から遷移してきたとき初期表示として入金日をセット
        if ($this->getRequest()->is(['get']) && $this->getRequest()->getParam('action') == 'add' && !is_null($this->getRequest()->getQuery('deposit_date'))) {
            $deposit_date = $this->getRequest()->getQuery('deposit_date');
            assert(is_string($deposit_date));
            $deposit->deposit_date = FrozenDate::parse($deposit_date);
        }

        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $deposit = $this->Deposits->patchEntity($deposit, $this->getRequest()->getData());
            if ($deposit->hasErrors()) {
                $this->Flash->set(implode('<br />', $deposit->getErrorMessages()), [
                    'escape' => false,
                    'element' => 'validation_error',
                    'params' => ['alert-class' => 'text-sm']
                ]);
            } else {
                $conn = $this->Deposits->getConnection();
                $conn->begin();
                if ($this->Deposits->save($deposit, ['atomic' => false])) {
                    $conn->commit();
                    $this->Flash->success('入出金の登録が完了しました。');

                    return $this->redirect(['action' => 'index', '?' => _code('InitialOrders.Deposits')]);
                }
                $conn->rollback();
            }
        }
        $this->set(compact('deposit'));
        return $this->render('edit');
    }

    /**
     * Delete method
     *
     * @param string|null $id 入出金ID
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     * @throws \Cake\Http\Exception\MethodNotAllowedException When request method invalid.
     */
    public function delete($id = null)
    {
        $this->getRequest()->allowMethod('delete');
        $entity = $this->Deposits->get($id);
        if ($this->Deposits->delete($entity)) {
            $this->Flash->success('入出金の削除が完了しました。');
        } else {
            $this->Flash->error('エラーが発生しました。');
        }

        return $this->redirect(['action' => 'index', '?' => _code('InitialOrders.Deposits')]);
    }

    /**
     * csv export method
     * @return void
     */
    public function csvExport()
    {
        $request = $this->getRequest()->getQueryParams();
        $deposits = $this->Deposits->getSearchQuery($request)->toArray();
        $extract = [
            // ID
            'id',
            // 入出金日
            function ($row) {
                return $row['deposit_date']?->i18nFormat('yyyy-MM-dd');
            },
            // 入出金額
            function ($row) {
                return !is_null($row['deposit_amount']) ? "{$row['deposit_amount']}円" : null;
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
        $this->response = $this->response->withDownload("deposits-{$datetime}.csv");
        $this->viewBuilder()->setClassName('CsvView.Csv');
        $this->viewBuilder()->setOptions([
            'serialize' => 'deposits',
            'header' => $this->Deposits->getCsvHeaders(),
            'extract' => $extract,
            'csvEncoding' => 'UTF-8'
        ]);
        $this->set(compact('deposits'));
    }
}
