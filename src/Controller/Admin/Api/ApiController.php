<?php
declare(strict_types=1);

namespace App\Controller\Admin\Api;

use App\Controller\Admin\AppController;
use Cake\Event\EventInterface;

/**
 * Api Controller
 */
class ApiController extends AppController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->loadComponent('RequestHandler');
        $this->viewBuilder()->setClassName('Json');
    }
}
