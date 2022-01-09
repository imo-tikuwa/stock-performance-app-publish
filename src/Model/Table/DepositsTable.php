<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use SoftDelete\Model\Table\SoftDeleteTrait;

/**
 * Deposits Model
 *
 * @method \App\Model\Entity\Deposit newEmptyEntity()
 * @method \App\Model\Entity\Deposit newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Deposit[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Deposit get($primaryKey, $options = [])
 * @method \App\Model\Entity\Deposit findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Deposit patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Deposit[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Deposit|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Deposit saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Deposit[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Deposit[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Deposit[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Deposit[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DepositsTable extends AppTable
{
    /** 論理削除を行う */
    use SoftDeleteTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('deposits');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
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

        // 入出金日
        $validator
            ->requirePresence('deposit_date', true, '入出金日を入力してください。')
            ->add('deposit_date', 'date', [
                'rule' => ['date', ['ymd']],
                'message' => '入出金日を正しく入力してください。',
                'last' => true
            ])
            ->add('deposit_date', 'validDate', [
                'rule' => function ($value) {
                    /** @var \App\Model\Table\CalendarsTable $table */
                    $table = TableRegistry::getTableLocator()->get('Calendars');
                    return $table->checkBusinessDay($value);
                },
                'message' => '入出金日に不正な値が入力されています。',
                'last' => true
            ])
            ->add('deposit_date', [
                'unique' => [
                    'rule' => 'validateUnique',
                    'provider' => Table::VALIDATOR_PROVIDER_NAME,
                    'message' => '入力した日付のデータが既に登録されています',
                    'last' => true
                ],
            ])
            ->notEmptyDate('deposit_date', '入出金日を入力してください。');

        // 入出金額
        $validator
            ->requirePresence('deposit_amount', true, '入出金額を入力してください。')
            ->add('deposit_amount', 'integer', [
                'rule' => 'isInteger',
                'message' => '入出金額を正しく入力してください。',
                'last' => true
            ])
            ->add('deposit_amount', 'greaterThanOrEqual', [
                'rule' => ['comparison', '>=', -100000000],
                'message' => '入出金額は-100000000以上の値で入力してください。',
                'last' => true
            ])
            ->add('deposit_amount', 'lessThanOrEqual', [
                'rule' => ['comparison', '<=', 100000000],
                'message' => '入出金額は100000000以下の値で入力してください。',
                'last' => true
            ])
            ->notEmptyString('deposit_amount', '入出金額を入力してください。');

        return $validator;
    }

    /**
     * patchEntityのオーバーライド
     * ファイル項目、GoogleMap項目のJSON文字列を配列に変換する
     *
     * @see \Cake\ORM\Table::patchEntity()
     * @param EntityInterface $entity エンティティ
     * @param array $data エンティティに上書きするデータ
     * @param array $options オプション配列
     * @return \App\Model\Entity\Deposit
     */
    public function patchEntity(EntityInterface $entity, array $data, array $options = []): EntityInterface
    {
        $entity = parent::patchEntity($entity, $data, $options);
        assert($entity instanceof \App\Model\Entity\Deposit);
        return $entity;
    }

    /**
     * ページネートに渡すクエリオブジェクトを生成する
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
        // 入出金日From
        if (isset($request['deposit_date_from']) && !is_null($request['deposit_date_from']) && $request['deposit_date_from'] !== '') {
            $query->where([$this->aliasField('deposit_date >=') => $request['deposit_date_from']]);
        }
        // 入出金日To
        if (isset($request['deposit_date_to']) && !is_null($request['deposit_date_to']) && $request['deposit_date_to'] !== '') {
            $query->where([$this->aliasField('deposit_date <=') => $request['deposit_date_to']]);
        }
        // 入出金額From
        if (isset($request['deposit_amount_from']) && !is_null($request['deposit_amount_from']) && $request['deposit_amount_from'] !== '') {
            $query->where([$this->aliasField('deposit_amount >=') => $request['deposit_amount_from']]);
        }
        // 入出金額To
        if (isset($request['deposit_amount_to']) && !is_null($request['deposit_amount_to']) && $request['deposit_amount_to'] !== '') {
            $query->where([$this->aliasField('deposit_amount <=') => $request['deposit_amount_to']]);
        }

        return $query;
    }

    /**
     * CSVヘッダー情報を取得する
     * @return array
     */
    public function getCsvHeaders()
    {
        return [
            'ID',
            '入出金日',
            '入出金額',
            '作成日時',
            '更新日時',
        ];
    }

    /**
     * CSVカラム情報を取得する
     * @return array
     */
    public function getCsvColumns()
    {
        return [
            'id',
            'deposit_date',
            'deposit_amount',
            'created',
            'modified',
        ];
    }
}
