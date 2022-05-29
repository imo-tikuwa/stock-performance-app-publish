<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\Validation\Validator;

/**
 * Calendars Model
 *
 * @method \App\Model\Entity\Calendar newEmptyEntity()
 * @method \App\Model\Entity\Calendar newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Calendar[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Calendar get($primaryKey, $options = [])
 * @method \App\Model\Entity\Calendar findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Calendar patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Calendar[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Calendar|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Calendar saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Calendar[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Calendar[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Calendar[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Calendar[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CalendarsTable extends AppTable
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

        $this->setTable('calendars');
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

        // 日付
        $validator
            ->requirePresence('day', true, '日付を入力してください。')
            ->add('day', 'date', [
                'rule' => ['date', ['ymd']],
                'message' => '日付を正しく入力してください。',
                'last' => true,
            ])
            ->notEmptyDate('day', '日付を入力してください。');

        // 休日？
        $validator
            ->requirePresence('is_holiday', true, '休日？を選択してください。')
            ->add('is_holiday', 'existIn', [
                'rule' => function ($value) {
                    return array_key_exists($value, _code('Codes.Calendars.is_holiday'));
                },
                'message' => '休日？に不正な値が含まれています。',
                'last' => true,
            ])
            ->notEmptyString('is_holiday', '休日？を選択してください。');

        // 休日名
        $validator
            ->requirePresence('holiday_name', true, '休日名を入力してください。')
            ->add('holiday_name', 'scalar', [
                'rule' => 'isScalar',
                'message' => '休日名を正しく入力してください。',
                'last' => true,
            ])
            ->add('holiday_name', 'maxLength', [
                'rule' => ['maxLength', 255],
                'message' => '休日名は255文字以内で入力してください。',
                'last' => true,
            ])
            ->notEmptyString('holiday_name', '休日名を入力してください。');

        return $validator;
    }

    /**
     * patchEntityのオーバーライド
     * ファイル項目、GoogleMap項目のJSON文字列を配列に変換する
     *
     * @see \Cake\ORM\Table::patchEntity()
     * @param \Cake\Datasource\EntityInterface $entity エンティティ
     * @param array $data エンティティに上書きするデータ
     * @param array $options オプション配列
     * @return \App\Model\Entity\Calendar
     */
    public function patchEntity(EntityInterface $entity, array $data, array $options = []): EntityInterface
    {
        $entity = parent::patchEntity($entity, $data, $options);
        assert($entity instanceof \App\Model\Entity\Calendar);

        return $entity;
    }

    /**
     * 年を元にリストを取得する
     *
     * @param string $year 年
     * @return array
     */
    public function findByYear($year)
    {
        return $this->find()->where([
            'day >=' => "{$year}-01-01",
            'day <=' => "{$year}-12-31",
        ])
        ->enableHydration(false)
        ->toArray();
    }

    /**
     * 営業日を全件取得する
     *
     * @return array
     */
    public function findBusinessDays()
    {
        return $this->find()
        ->select(['day'])
        ->where(['is_holiday' => false])
        ->enableHydration(false)
        ->toArray();
    }

    /**
     * FromToの日付を元に営業日を取得する
     *
     * @param string $day_from From日付文字列
     * @param string $day_to To日付文字列
     * @return array
     */
    public function findDisplayTargetDates($day_from, $day_to)
    {
        $results = [];
        $business_days = $this->find()
        ->select(['day'])
        ->where([
            'is_holiday' => false,
            'day >=' => $day_from,
            'day <=' => $day_to,
        ])
        ->toArray();

        if (empty($business_days)) {
            return [];
        }

        foreach ($business_days as $business_day) {
            $results[] = $business_day->day->i18nFormat('yyyy-MM-dd');
        }

        return $results;
    }

    /**
     * 引数の日付が営業日か判定する
     *
     * @param string $date_str 日付文字列
     * @return bool trueのとき引数の日付は営業日
     */
    public function checkBusinessDay($date_str = null)
    {
        if (is_null($date_str)) {
            return false;
        }

        $result = $this->find()
        ->where([
            'is_holiday' => false,
            'day' => $date_str,
        ])
        ->count();

        return $result > 0;
    }
}
