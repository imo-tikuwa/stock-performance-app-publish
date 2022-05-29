<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Form\SearchForm;
use App\Utils\AuthUtils;
use Cake\Event\EventInterface;
use Cake\Utility\Text;
use PHPGangsta_GoogleAuthenticator;

/**
 * Account Controller
 *
 * @property \App\Model\Table\AdminsTable $Admins
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
     * @var string OpenAPIトークンを生成する
     */
    private const CREATE_OPENAPI_TOKEN = 'create';

    /**
     * @var string OpenAPIトークンを更新する
     */
    private const MODIFY_OPENAPI_TOKEN = 'modify';

    /**
     * @var string OpenAPIトークンを削除する
     */
    private const DELETE_OPENAPI_TOKEN = 'delete';

    /**
     * @var array OpenAPI token choices.
     */
    private const OPENAPI_CHECKBOX_SELECTIONS = [
        self::CREATE_OPENAPI_TOKEN => '生成する',
        self::MODIFY_OPENAPI_TOKEN => '更新する',
        self::DELETE_OPENAPI_TOKEN => '削除する',
    ];

    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->Admins = $this->fetchTable('Admins');

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
     *
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
            /** @var \App\Model\Entity\Admin $admin */
            $admin = $this->Admins->newEmptyEntity();
        }
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $admin = $this->Admins->patchEntity($admin, $this->getRequest()->getData());
            if ($admin->hasErrors()) {
                $this->Flash->set(implode('<br />', $admin->getErrorMessages()), [
                    'escape' => false,
                    'element' => 'validation_error',
                    'params' => ['alert-class' => 'text-sm'],
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
                        $admin = $this->Admins->patchEntity($admin, ['otp_secret' => $otp_secret], ['validate' => false]);
                    } else {
                        $admin = $this->Admins->patchEntity($admin, ['otp_secret' => null], ['validate' => false]);
                    }
                }

                // OpenAPIトークンの生成/更新/削除
                $mode_api_token = $this->getRequest()->getData('mode_api_token');
                if (is_string($mode_api_token) && array_key_exists($mode_api_token, self::OPENAPI_CHECKBOX_SELECTIONS)) {
                    switch ($mode_api_token) {
                        case self::CREATE_OPENAPI_TOKEN:
                        case self::MODIFY_OPENAPI_TOKEN:
                            $admin = $this->Admins->patchEntity($admin, ['api_token' => Text::uuid()], ['validate' => false]);
                            break;
                        case self::DELETE_OPENAPI_TOKEN:
                            $admin = $this->Admins->patchEntity($admin, ['api_token' => null], ['validate' => false]);
                            break;
                    }
                }

                $api_token_touched = $admin->isDirty('api_token');
                $entity_status = !$admin->isNew() ? '更新' : '登録';

                if ($this->Admins->save($admin, ['atomic' => false])) {
                    // 二段階認証 無効→有効 と切り替えたとき二段階認証用のQRコードを生成
                    if ($is_use_otp_dirty && $admin->use_otp === true) {
                        // @phpstan-ignore-next-line
                        $qr_url = $google_authenticator->getQRCodeGoogleUrl(AuthUtils::getTwoFactorQrName($admin), $otp_secret, SITE_NAME);
                        $this->getRequest()->getSession()->write('qr_url', $qr_url);
                    }

                    $conn->commit();
                    $message = "アカウントの{$entity_status}が完了しました。";
                    if ($api_token_touched) {
                        if (is_null($admin->api_token)) {
                            $message .= '<br />OpenAPIトークンを削除しました。';
                        } else {
                            $api_token_status = $mode_api_token === self::MODIFY_OPENAPI_TOKEN ? '更新' : '登録';
                            $message .= "<br />OpenAPIトークンを以下の値で{$api_token_status}しました。<br />{$admin->api_token}";
                        }
                    }
                    $this->Flash->success($message, ['escape' => false]);

                    return $this->redirect(['action' => 'index']);
                }
                $conn->rollback();
            }
        }

        // 画面に渡すOpenAPIトークンの選択肢を作成
        $api_token_selections = self::OPENAPI_CHECKBOX_SELECTIONS;
        if (is_null($admin->api_token)) {
            unset($api_token_selections[self::MODIFY_OPENAPI_TOKEN]);
            unset($api_token_selections[self::DELETE_OPENAPI_TOKEN]);
        } else {
            unset($api_token_selections[self::CREATE_OPENAPI_TOKEN]);
        }

        $this->set(compact('admin', 'api_token_selections'));

        return $this->render('edit');
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
        if ($id === strval(SUPER_USER_ID)) {
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
