<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class AppTable extends Table
{
    /**
     * 初期化処理
     * {@inheritDoc}
     * @see \Cake\ORM\Table::initialize()
     *
     * @param array $config Configuration options passed to the constructor
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        /** 作成日時、更新日時の自動付与 */
        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created' => 'new',
                    'modified' => 'always'
                ]
            ]
        ]);
    }
}
