<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use App\Form\SearchForm;
use App\Utils\AuthUtils;
use Cake\Event\EventInterface;
use Cake\I18n\FrozenDate;
use Cake\I18n\FrozenTime;
use Cake\Utility\Hash;
use PHPGangsta_GoogleAuthenticator;

/**
 * Account Controller
 *
 * @property \App\Model\Table\AdminsTable $Admins
 *
 * @method \App\Model\Entity\Admin[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AccountController extends AppController
{

    /**
     * Paging setting.
     */
    public $paginate = [
        'limit' => 20,
    ];

    /**
     *
     * {@inheritDoc}
     * @see \App\Controller\Admin\AppController::beforeFilter()
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->loadModel('Admins');

        // 管理者以外はアクセス不可
        if (!AuthUtils::isSuperUser($this->getRequest())) {
            $this->Flash->error(MESSAGE_AUTH_ERROR);

            return $this->redirect(['controller' => 'top', 'action' => 'index']);
        }
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $request = $this->getRequest()->getQueryParams();
        $query = $this->_getQuery($request);
        $accounts = $this->paginate($query);
        $search_form = new SearchForm();
        $search_form->setData($request);

        // セッションに二段階認証用のQRコードがあればビューに渡す(セッションからは削除)
        $session = $this->getRequest()->getSession();
        if ($session->check('qr_url')) {
            $this->set('qr_url', $session->consume('qr_url'));
        }

        $this->set(compact('accounts', 'search_form'));
    }

    /**
     * ページネートに渡すクエリオブジェクトを生成する
     * @param array $request リクエスト情報
     * @return \Cake\ORM\Query $query
     */
    private function _getQuery($request)
    {
        $query = $this->Admins->find();
        // ID
        if (isset($request['id']) && !is_null($request['id']) && $request['id'] !== '') {
            $query->where([$this->Admins->aliasField('id') => $request['id']]);
        }
        // 名前
        if (isset($request['name']) && !is_null($request['name']) && $request['name'] !== '') {
            $query->where([$this->Admins->aliasField('name LIKE') => "%{$request['name']}%"]);
        }
        // メールアドレス
        if (isset($request['mail']) && !is_null($request['mail']) && $request['mail'] !== '') {
            $query->where([$this->Admins->aliasField('mail LIKE') => "%{$request['mail']}%"]);
        }

        return $query;
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
     * @param string|null $id アカウントID
     * @return \Cake\Http\Response|null
     */
    public function edit($id = null)
    {
        return $this->_form($id);
    }

    /**
     * Add and Edit Common method
     *
     * @param string|null $id アカウントID
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    private function _form($id = null)
    {
        if ($this->getRequest()->getParam('action') == 'edit') {
            $admin = $this->Admins->get($id);
            $this->Admins->touch($admin);
        } else {
            $admin = $this->Admins->newEmptyEntity();
        }
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $admin = $this->Admins->patchEntity($admin, $this->getRequest()->getData());
            if ($admin->hasErrors()) {
                $this->Flash->set(implode('<br />', $admin->getErrorMessages()), [
                    'escape' => false,
                    'element' => 'validation_error',
                    'params' => ['alert-class' => 'text-sm']
                ]);
            } else {
                $conn = $this->Admins->getConnection();
                $conn->begin();

                // 二段階認証 無効→有効 と切り替えたとき二段階認証用のシークレットキーを生成
                // 有効→無効 と切り替えたときシークレットキーを削除
                $is_use_otp_dirty = $admin->isDirty('use_otp');
                if ($is_use_otp_dirty) {
                    if ($admin->use_otp === true) {
                        $google_authenticator = new PHPGangsta_GoogleAuthenticator();
                        $otp_secret = $google_authenticator->createSecret(GOOGLE_AUTHENTICATOR_SECRET_KEY_LEN);
                        $admin = $this->Admins->patchEntity($admin, ['otp_secret'=> $otp_secret], ['validate' => false]);
                    } else {
                        $admin = $this->Admins->patchEntity($admin, ['otp_secret'=> null], ['validate' => false]);
                    }
                }

                if ($this->Admins->save($admin, ['atomic' => false])) {
                    // 二段階認証 無効→有効 と切り替えたとき二段階認証用のQRコードを生成
                    if ($is_use_otp_dirty && $admin->use_otp === true) {
                        $qr_url = $google_authenticator->getQRCodeGoogleUrl(AuthUtils::getTwoFactorQrName($admin), $otp_secret, SITE_NAME);
                        $this->getRequest()->getSession()->write('qr_url', $qr_url);
                    }

                    $conn->commit();
                    $this->Flash->success('アカウントの登録が完了しました。');

                    return $this->redirect(['action' => 'index']);
                }
                $conn->rollback();
            }
        }
        $this->set(compact('admin'));
        $this->render('edit');
    }

    /**
     * Delete method
     *
     * @param string|null $id 管理者ID
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        if (SUPER_USER_ID == $id) {
            $this->Flash->error('エラーが発生しました。');

            return $this->redirect(['action' => 'index']);
        }
        $this->getRequest()->allowMethod(['post', 'delete']);
        $entity = $this->Admins->get($id);
        if ($this->Admins->delete($entity)) {
            $this->Flash->success('管理者の削除が完了しました。');
        } else {
            $this->Flash->error('エラーが発生しました。');
        }

        return $this->redirect(['action' => 'index']);
    }
}
