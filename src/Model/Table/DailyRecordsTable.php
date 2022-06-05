<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * DailyRecords Model
 *
 * @property \App\Model\Table\AccountsTable&\Cake\ORM\Association\BelongsTo $Accounts
 * @method \App\Model\Entity\DailyRecord newEmptyEntity()
 * @method \App\Model\Entity\DailyRecord newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\DailyRecord[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DailyRecord get($primaryKey, $options = [])
 * @method \App\Model\Entity\DailyRecord findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\DailyRecord patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DailyRecord[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\DailyRecord|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DailyRecord saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DailyRecord[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\DailyRecord[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\DailyRecord[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\DailyRecord[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DailyRecordsTable extends AppTable
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('daily_records');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->belongsTo('Accounts', [
            'foreignKey' => 'account_id',
            'joinType' => 'INNER',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        // ID
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        // 口座名
        $validator
            ->requirePresence('account_id', true, '口座名を選択してください。')
            ->add('account_id', 'integer', [
                'rule' => 'isInteger',
                'message' => '口座名を正しく入力してください。',
                'last' => true,
            ])
            ->add('account_id', 'existForeignEntity', [
                'rule' => function ($account_id) {
                    $table = TableRegistry::getTableLocator()->get('Accounts');
                    $entity = $table->find()->select(['id'])->where(['id' => $account_id])->first();

                    return !empty($entity);
                },
                'message' => '口座名に不正な値が入力されています。',
                'last' => true,
            ])
            ->notEmptyString('account_id', '口座名を選択してください。');

        // 日付
        $validator
            ->requirePresence('day', true, '日付を入力してください。')
            ->add('day', 'date', [
                'rule' => ['date', ['ymd']],
                'message' => '日付を正しく入力してください。',
                'last' => true,
            ])
            ->add('day', 'validDate', [
                'rule' => function ($value) {
                    /** @var \App\Model\Table\CalendarsTable $table */
                    $table = TableRegistry::getTableLocator()->get('Calendars');

                    return $table->checkBusinessDay($value);
                },
                'message' => '日付に不正な値が入力されています。',
                'last' => true,
            ])
            ->add('day', [
                'unique' => [
                    'rule' => [
                        'validateUnique', [
                            'scope' => 'account_id',
                        ],
                    ],
                    'provider' => Table::VALIDATOR_PROVIDER_NAME,
                    'message' => '入力した日付のデータは既に登録されています',
                    'last' => true,
                ],
            ])
            ->notEmptyDate('day', '日付を入力してください。');

        // 資産額
        $validator
            ->requirePresence('record', true, '資産額を入力してください。')
            ->add('record', 'integer', [
                'rule' => 'isInteger',
                'message' => '資産額を正しく入力してください。',
                'last' => true,
            ])
            ->add('record', 'greaterThanOrEqual', [
                'rule' => ['comparison', '>=', 0],
                'message' => '資産額は0以上の値で入力してください。',
                'last' => true,
            ])
            ->add('record', 'lessThanOrEqual', [
                'rule' => ['comparison', '<=', 1000000000],
                'message' => '資産額は1000000000以下の値で入力してください。',
                'last' => true,
            ])
            ->notEmptyString('record', '資産額を入力してください。');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['account_id'], 'Accounts'));

        return $rules;
    }

    /**
     * patchEntityのオーバーライド
     * ファイル項目、GoogleMap項目のJSON文字列を配列に変換する
     *
     * @see \Cake\ORM\Table::patchEntity()
     * @param \Cake\Datasource\EntityInterface $entity エンティティ
     * @param array $data エンティティに上書きするデータ
     * @param array $options オプション配列
     * @return \App\Model\Entity\DailyRecord
     */
    public function patchEntity(EntityInterface $entity, array $data, array $options = []): EntityInterface
    {
        $entity = parent::patchEntity($entity, $data, $options);
        assert($entity instanceof \App\Model\Entity\DailyRecord);

        return $entity;
    }

    /**
     * ページネートに渡すクエリオブジェクトを生成する
     *
     * @param array $request リクエスト情報
     * @return \Cake\ORM\Query $query
     */
    public function getSearchQuery($request)
    {
        $query = $this->find();
        // ID
        if (isset($request['id']) && !is_null($request['id']) && $request['id'] !== '') {
            $query->where([$this->aliasField('id') => $request['id']]);
        }
        // 口座名
        if (isset($request['account_id']) && !is_null($request['account_id']) && $request['account_id'] !== '') {
            $query->where(['Accounts.id' => $request['account_id']]);
        }
        // 日付From
        if (isset($request['day_from']) && !is_null($request['day_from']) && $request['day_from'] !== '') {
            $query->where([$this->aliasField('day >=') => $request['day_from']]);
        }
        // 日付To
        if (isset($request['day_to']) && !is_null($request['day_to']) && $request['day_to'] !== '') {
            $query->where([$this->aliasField('day <=') => $request['day_to']]);
        }
        // 資産額From
        if (isset($request['record_from']) && !is_null($request['record_from']) && $request['record_from'] !== '') {
            $query->where([$this->aliasField('record >=') => $request['record_from']]);
        }
        // 資産額To
        if (isset($request['record_to']) && !is_null($request['record_to']) && $request['record_to'] !== '') {
            $query->where([$this->aliasField('record <=') => $request['record_to']]);
        }
        $query->group('DailyRecords.id');

        return $query->contain(['Accounts']);
    }

    /**
     * CSVヘッダー情報を取得する
     *
     * @return array
     */
    public function getCsvHeaders()
    {
        return [
            'ID',
            '口座名',
            '日付',
            '資産額',
            '作成日時',
            '更新日時',
        ];
    }

    /**
     * CSVカラム情報を取得する
     *
     * @return array
     */
    public function getCsvColumns()
    {
        return [
            'id',
            'account_id',
            'day',
            'record',
            'created',
            'modified',
        ];
    }
}
