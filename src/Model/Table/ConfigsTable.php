<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\Validation\Validator;

/**
 * Configs Model
 *
 * @method \App\Model\Entity\Config newEmptyEntity()
 * @method \App\Model\Entity\Config newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Config[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Config get($primaryKey, $options = [])
 * @method \App\Model\Entity\Config findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Config patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Config[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Config|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Config saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Config[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Config[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Config[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Config[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ConfigsTable extends AppTable
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

        $this->setTable('configs');
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

        // 月ごと表示モード
        $validator
            ->requirePresence('display_only_month', true, '月ごと表示モードを選択してください。')
            ->add('display_only_month', 'scalar', [
                'rule' => 'isScalar',
                'message' => '月ごと表示モードを正しく入力してください。',
                'last' => true
            ])
            ->add('display_only_month', 'maxLength', [
                'rule' => ['maxLength', 2],
                'message' => '月ごと表示モードは2文字以内で入力してください。',
                'last' => true
            ])
            ->add('display_only_month', 'existIn', [
                'rule' => function ($value) {
                    return array_key_exists($value, _code('Codes.Configs.display_only_month'));
                },
                'message' => '月ごと表示モードに不正な値が含まれています。',
                'last' => true
            ])
            ->notEmptyString('display_only_month', '月ごと表示モードを選択してください。');

        // 初期資産額表示
        $validator
            ->requirePresence('display_init_record', true, '初期資産額表示を選択してください。')
            ->add('display_init_record', 'scalar', [
                'rule' => 'isScalar',
                'message' => '初期資産額表示を正しく入力してください。',
                'last' => true
            ])
            ->add('display_init_record', 'maxLength', [
                'rule' => ['maxLength', 2],
                'message' => '初期資産額表示は2文字以内で入力してください。',
                'last' => true
            ])
            ->add('display_init_record', 'existIn', [
                'rule' => function ($value) {
                    return array_key_exists($value, _code('Codes.Configs.display_init_record'));
                },
                'message' => '初期資産額表示に不正な値が含まれています。',
                'last' => true
            ])
            ->notEmptyString('display_init_record', '初期資産額表示を選択してください。');

        // 実質資産のチャートカラー
        $validator
            ->requirePresence('record_total_real_color', true, '実質資産のチャートカラーを入力してください。')
            ->add('record_total_real_color', 'lengthBetween', [
                'rule' => ['lengthBetween', 6, 6],
                'message' => '実質資産のチャートカラーは6桁の16進数で入力してください。。',
                'last' => true
            ])
            ->add('record_total_real_color', 'correctDigit', [
                'rule' => function ($value) {
                    return ctype_xdigit($value);
                },
                'message' => '実質資産のチャートカラーは6桁の16進数で入力してください。',
                'last' => true
            ])
            ->notEmptyString('record_total_real_color', '実質資産のチャートカラーを入力してください。');

        // 初期資産のチャートカラー
        $validator
            ->requirePresence('init_record_color', true, '初期資産のチャートカラーを入力してください。')
            ->add('init_record_color', 'lengthBetween', [
                'rule' => ['lengthBetween', 6, 6],
                'message' => '初期資産のチャートカラーは6桁の16進数で入力してください。。',
                'last' => true
            ])
            ->add('init_record_color', 'correctDigit', [
                'rule' => function ($value) {
                    return ctype_xdigit($value);
                },
                'message' => '初期資産のチャートカラーは6桁の16進数で入力してください。',
                'last' => true
            ])
            ->notEmptyString('init_record_color', '初期資産のチャートカラーを入力してください。');

        // 表示項目設定
        $validator
            ->requirePresence('display_setting', false, '表示項目設定を選択してください。')
            ->add('display_setting', 'existIn', [
                'rule' => function ($values) {
                    foreach ($values as $value) {
                        if (!array_key_exists($value, _code('Codes.Configs.display_setting'))) {
                            return false;
                        }
                    }
                    return true;
                },
                'message' => '表示項目設定に不正な値が含まれています。',
                'last' => true
            ])
            ->allowEmptyArray('display_setting');

        // ChromeDriverのパス
        $validator
            ->add('chromedriver_path', 'scalar', [
                'rule' => 'isScalar',
                'message' => 'ChromeDriverのパスを正しく入力してください。',
                'last' => true
            ])
            ->add('chromedriver_path', 'maxLength', [
                'rule' => ['maxLength', 255],
                'message' => 'ChromeDriverのパスは255文字以内で入力してください。',
                'last' => true
            ])
            ->allowEmptyString('chromedriver_path');

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
     * @return \App\Model\Entity\Config
     */
    public function patchEntity(EntityInterface $entity, array $data, array $options = []): EntityInterface
    {
        // 表示項目設定が未チェックのときカラムをnullで更新する
        if (!isset($data['display_setting'])) {
            $data['display_setting'] = null;
        }
        return parent::patchEntity($entity, $data, $options);
    }
}
