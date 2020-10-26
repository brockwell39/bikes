<?php
namespace App\Model\Table;

use Cake\I18n\Time;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Bicycles Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Bicycle get($primaryKey, $options = [])
 * @method \App\Model\Entity\Bicycle newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Bicycle[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Bicycle|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Bicycle saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Bicycle patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Bicycle[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Bicycle findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BicyclesTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('bicycles');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Josegonzalez/Upload.Upload', [
            'photo',
        ]);

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('title')
            ->maxLength('title', 255)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('type')
            ->maxLength('type', 4)
            ->requirePresence('type', 'create')
            ->notEmptyString('type');

        $validator
            ->scalar('gender')
            ->maxLength('gender', 7)
            ->requirePresence('gender', 'create')
            ->notEmptyString('gender');

        $validator
            ->integer('gears')
            ->requirePresence('gears', 'create')
            ->notEmptyString('gears');



       $validator
            ->scalar('photo')
            ->maxLength('photo', 255)
            ->requirePresence('photo', 'create')
            ->notEmptyString('photo');

       $validator
            ->scalar('dir')
            ->maxLength('dir', 255)
            ->requirePresence('dir', 'create')
            ->notEmptyString('dir');

       $validator
            ->integer('weekday_price')
            ->requirePresence('weekday_price', 'create')
            ->notEmptyString('weekday_price');

       $validator
            ->integer('weekend_price')
            ->requirePresence('weekend_price', 'create')
            ->notEmptyString('weekend_price');

        return $validator;
    }

    /**
     * @return bool
     */

    public function getWeekAhead(){
        $now = Time::now('Europe/London')->setTime(9, 00);
        $now1 = Time::now('Europe/London')->addDays(1);
        $now2 = Time::now('Europe/London')->addDays(2);
        $now3 = Time::now('Europe/London')->addDays(3);
        $now4 = Time::now('Europe/London')->addDays(4);
        $now5 = Time::now('Europe/London')->addDays(5);
        $now6 = Time::now('Europe/London')->addDays(6);
        $week_ahead = [$now, $now1, $now2, $now3, $now4, $now5, $now6,];
        return $week_ahead;
    }
    public function getSlotsAhead(){
        $now = Time::now('Europe/London')->setTime(9, 00);
        $now1 = Time::now('Europe/London')->addDays(1)->setTime(9, 00);
        $now2 = Time::now('Europe/London')->addDays(2)->setTime(9, 00);
        $now3 = Time::now('Europe/London')->addDays(3)->setTime(9, 00);
        $now4 = Time::now('Europe/London')->addDays(4)->setTime(9, 00);
        $now5 = Time::now('Europe/London')->addDays(5)->setTime(9, 00);
        $now6 = Time::now('Europe/London')->addDays(6)->setTime(9, 00);
        $nowpm = Time::now('Europe/London')->setTime(13, 00);
        $now1pm = Time::now('Europe/London')->addDays(1)->setTime(13, 00);
        $now2pm = Time::now('Europe/London')->addDays(2)->setTime(13, 00);
        $now3pm = Time::now('Europe/London')->addDays(3)->setTime(13, 00);
        $now4pm = Time::now('Europe/London')->addDays(4)->setTime(13, 00);
        $now5pm = Time::now('Europe/London')->addDays(5)->setTime(13, 00);
        $now6pm = Time::now('Europe/London')->addDays(6)->setTime(13, 00);
        $slots_ahead = [$now,$now1,$now2,$now3,$now4,$now5,$now6,$nowpm,$now1pm,$now2pm,$now3pm,$now4pm,$now5pm,$now6pm];
        return $slots_ahead;
    }
    public function getAvailibility($id){
        // get bookings for the week ahead
        $bookingsTable = TableRegistry::getTableLocator()->get('Bookings');
        $availibility = $bookingsTable->find()->where(['bike_id'=>$id]);
        $slots_ahead = $this->getSlotsAhead();
        $bookings =[];
        foreach($availibility as $book){
            $bookings[] = $book->booking_start;
        }
        $bookings_to_view = [];
        foreach ($slots_ahead as $day) {
            if(in_array($day,$bookings)){
                array_push($bookings_to_view, 'BOOKED');
            }
            else{
                array_push($bookings_to_view, $day);
            }
        }
        return $bookings_to_view;
    }


    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
