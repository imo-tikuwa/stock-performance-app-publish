<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use Cake\Event\EventInterface;

/**
 * Auth Controller
 *
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication
 */
class AuthController extends AppController
{
    /**
     *
     * {@inheritDoc}
     * @see \Cake\Controller\Controller::beforeFilter()
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        // You can prevent infinite redirect loop issues by setting the login action so that it does not require authentication.
        $this->Authentication->addUnauthenticatedActions(['login', 'secureLogin']);

        $this->viewBuilder()->disableAutoLayout();
    }

    /**
     * ログイン
     * @return \Cake\Http\Response|void
     */
    public function login()
    {
        return $this->_login();
    }

    /**
     * 二段階認証付きログイン
     * @return \Cake\Http\Response|void
     */
    public function secureLogin()
    {
        return $this->_login();
    }

    /**
     * 共通ログイン処理
     * @return \Cake\Http\Response|void
     */
    private function _login()
    {
        $this->getRequest()->allowMethod(['get', 'post']);
        $result = $this->Authentication->getResult();
        if ($result->isValid()) {
            $target = $this->Authentication->getLoginRedirect() ?? '/admin/top';

            return $this->redirect($target);
        }
        if ($this->getRequest()->is('post') && !$result->isValid()) {
            $error_message = ($this->getRequest()->getParam('action') === 'secureLogin') ? 'ログインID/パスワード/認証コードのいずれかが正しくありません。' : 'ログインID/パスワードのいずれかが正しくありません。';
            $this->Flash->error($error_message);
        }
        $this->render('login');
    }

    /**
     * ログアウト
     * @return \Cake\Http\Response|void
     */
    public function logout()
    {
        $result = $this->Authentication->getResult();
        if ($result->isValid()) {
            $redirect_login_action = $this->getRequest()->getSession()->read('Auth.Admin.use_otp') ? 'secureLogin' : 'login';
            $this->Authentication->logout();
            return $this->redirect(['action' => $redirect_login_action]);
        }
    }
}
