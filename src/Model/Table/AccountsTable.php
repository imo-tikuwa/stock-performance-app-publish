<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Accounts Model
 *
 * @property \App\Model\Table\DailyRecordsTable&\Cake\ORM\Association\HasMany $DailyRecords
 *
 * @method \App\Model\Entity\Account newEmptyEntity()
 * @method \App\Model\Entity\Account newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Account[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Account get($primaryKey, $options = [])
 * @method \App\Model\Entity\Account findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Account patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Account[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Account|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Account saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Account[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Account[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Account[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Account[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AccountsTable extends AppTable
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

        $this->setTable('accounts');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
        $this->hasMany('DailyRecords', [
            'foreignKey' => 'account_id',
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
            ->requirePresence('name', true, '口座名を入力してください。')
            ->add('name', 'scalar', [
                'rule' => 'isScalar',
                'message' => '口座名を正しく入力してください。',
                'last' => true
            ])
            ->add('name', 'maxLength', [
                'rule' => ['maxLength', 255],
                'message' => '口座名は255文字以内で入力してください。',
                'last' => true
            ])
            ->notEmptyString('name', '口座名を入力してください。');

        // 初期資産額
        $validator
            ->requirePresence('init_record', true, '初期資産額を入力してください。')
            ->add('init_record', 'integer', [
                'rule' => 'isInteger',
                'message' => '初期資産額を正しく入力してください。',
                'last' => true
            ])
            ->add('init_record', 'greaterThanOrEqual', [
                'rule' => ['comparison', '>=', 0],
                'message' => '初期資産額は0以上の値で入力してください。',
                'last' => true
            ])
            ->add('init_record', 'lessThanOrEqual', [
                'rule' => ['comparison', '<=', 1000000000],
                'message' => '初期資産額は1000000000以下の値で入力してください。',
                'last' => true
            ])
            ->notEmptyString('init_record', '初期資産額を入力してください。');

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
     * @return \App\Model\Entity\Account
     */
    public function patchEntity(EntityInterface $entity, array $data, array $options = []): EntityInterface
    {
        $entity = parent::patchEntity($entity, $data, $options);
        assert($entity instanceof \App\Model\Entity\Account);
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
        // 口座名
        if (isset($request['name']) && !is_null($request['name']) && $request['name'] !== '') {
            $query->where([$this->aliasField('name LIKE') => "%{$request['name']}%"]);
        }
        // 初期資産額From
        if (isset($request['init_record_from']) && !is_null($request['init_record_from']) && $request['init_record_from'] !== '') {
            $query->where([$this->aliasField('init_record >=') => $request['init_record_from']]);
        }
        // 初期資産額To
        if (isset($request['init_record_to']) && !is_null($request['init_record_to']) && $request['init_record_to'] !== '') {
            $query->where([$this->aliasField('init_record <=') => $request['init_record_to']]);
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
            '口座名',
            '初期資産額',
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
            'name',
            'init_record',
            'created',
            'modified',
        ];
    }
}
