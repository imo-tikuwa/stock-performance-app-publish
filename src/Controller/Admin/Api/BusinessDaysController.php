<?php
namespace App\Controller\Admin\Api;

use App\Controller\Admin\Api\ApiController;
use Cake\Utility\Hash;

/**
 * BusinessDays Controller
 *
 * @property \App\Model\Table\CalendarsTable $Calendars
 */
class BusinessDaysController extends ApiController
{
    /**
     *
     * @return void
     */
    public function index()
    {
        $this->Calendars = $this->fetchTable('Calendars');
        $results = $this->Calendars->findBusinessDays();
        $results = Hash::extract($results, '{n}.day');
        $this->set([
            'results' => $results,
            '_serialize' => 'results',
            '_jsonOptions' => JSON_UNESCAPED_UNICODE
        ]);
    }
}
