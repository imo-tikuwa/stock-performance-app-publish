<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class AppTable extends Table
{
    /**
     * @inheritDoc
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        /** 作成日時、更新日時の自動付与 */
        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created' => 'new',
                    'modified' => 'always',
                ],
            ],
        ]);
    }
}
