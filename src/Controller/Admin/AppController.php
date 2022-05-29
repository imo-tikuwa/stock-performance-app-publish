<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Utils\AuthUtils;
use Cake\Event\EventInterface;
use Cake\Http\ServerRequest;

/**
 * Admin AppController
 *
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication
 */
class AppController extends \App\Controller\AppController
{
    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();

        /** load \Authentication\Controller\Component\AuthenticationComponent */
        $this->loadComponent('Authentication.Authentication');

        $this->viewBuilder()->setLayout('default_admin');
    }

    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        // ログインチェック
        if (!$this->Authentication->getResult()?->isValid()) {
            return;
        }

        // 権限チェック
        if (!$this->authorize($this->getRequest())) {
            $this->Flash->error(MESSAGE_AUTH_ERROR);

            return $this->redirect(['controller' => 'Top', 'action' => 'index']);
        }
    }

    /**
     * cakephp/authorizationプラグインの代わりの認可処理
     *
     * @param \Cake\Http\ServerRequest $request リクエスト情報
     * @return bool
     */
    private function authorize(ServerRequest $request): bool
    {
        // 権限なしでアクセス可能なコントローラ
        if (in_array($request->getParam('controller'), ['Auth', 'Top'])) {
            return true;
        }

        // ログイン中のユーザーがアクセス中のアクションの権限を持っているかチェック
        return AuthUtils::hasRole($request);
    }
}
