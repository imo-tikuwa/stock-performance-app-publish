<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\Validation\Validator;
use SoftDelete\Model\Table\SoftDeleteTrait;

/**
 * Admins Model
 *
 * @method \App\Model\Entity\Admin get($primaryKey, $options = [])
 * @method \App\Model\Entity\Admin newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Admin[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Admin|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Admin saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Admin patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Admin[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Admin findOrCreate($search, callable $callback = null, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AdminsTable extends AppTable
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

        $this->setTable('admins');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
    }

    /**
     * auth finder Method.
     *
     * @param \Cake\ORM\Query $query query object
     * @param array $options option array
     * @return \Cake\ORM\Query
     */
    public function findAuth(Query $query, array $options): Query
    {
        $query->select(['id', 'name', 'mail', 'password', 'use_otp', 'otp_secret', 'privilege', 'api_token']);

        return $query;
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): \Cake\Validation\Validator
    {
        // ID
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        // 名前
        $validator
            ->requirePresence('name', true, '名前を入力してください。')
            ->add('name', 'scalar', [
                'rule' => 'isScalar',
                'message' => '名前を正しく入力してください。',
                'last' => true,
            ])
            ->add('name', 'maxLength', [
                'rule' => ['maxLength', 255],
                'message' => '名前は255文字以内で入力してください。',
                'last' => true,
            ])
            ->notEmptyString('name', '名前を入力してください。');

        // メールアドレス
        $validator
            ->requirePresence('mail', true, 'メールアドレスを入力してください。')
            ->add('mail', 'email', [
                'rule' => 'email',
                'message' => 'メールアドレスを正しく入力してください。',
                'last' => true,
            ])
            ->add('mail', 'maxLength', [
                'rule' => ['maxLength', 255],
                'message' => 'メールアドレスは255文字以内で入力してください。',
                'last' => true,
            ])
            ->add('mail', 'checkUnique', [
                'rule' => function ($value, $context) {
                    $conditions = ['mail' => $value];
                    if (isset($context['data']['id'])) {
                        $conditions['id <>'] = $context['data']['id'];
                    }

                    return !$this->exists($conditions);
                },
                'message' => '入力されたメールアドレスのアカウントは既に存在します。',
                'last' => true,
            ])
            ->notEmptyString('mail', 'メールアドレスを入力してください。');

        // パスワード
        $validator
            ->requirePresence('password', true, 'パスワードを入力してください。')
            ->add('password', 'scalar', [
                'rule' => 'isScalar',
                'message' => 'パスワードを正しく入力してください。',
                'last' => true,
            ])
            ->add('password', 'custom', [
                'rule' => function ($value) {
                    return (bool)preg_match('/^[a-zA-Z0-9!-\/:-@\[-~]+$/u', $value);
                },
                'message' => 'パスワードは半角英字、半角数字、半角記号で入力してください。',
                'last' => true,
            ])
            ->add('password', 'lengthBetween', [
                'rule' => ['lengthBetween', 8, 20],
                'message' => 'パスワードは8文字以上20文字以下で入力してください。',
                'last' => true,
            ])
            ->notEmptyString('password', 'パスワードを入力してください。');

        // 二段階認証
        $validator
            ->requirePresence('use_otp', true, '二段階認証が不正です。')
            ->add('use_otp', 'isOn', [
                'rule' => function ($value) {
                    return in_array($value, ['0', '1'], true);
                },
                'message' => '二段階認証が不正です。',
                'last' => true,
            ])
            ->allowEmptyString('use_otp');

        // 二段階認証用シークレットキー
        $validator
            ->allowEmptyString('otp_secret');

        // 権限
        $validator
            ->allowEmptyString('privilege');

        // OpenAPIトークン
        $validator
            ->allowEmptyString('api_token');

        return $validator;
    }
}
