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
        $this->hasMany('Bookings', [
            'foreignKey' => 'bike_id',
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
            ->integer('weekday_price')
            ->requirePresence('weekday_price', 'create')
            ->notEmptyString('weekday_price');

       $validator
            ->integer('weekend_price')
            ->requirePresence('weekend_price', 'create')
            ->notEmptyString('weekend_price');

        $validator
            ->integer('deposit')
            ->requirePresence('deposit', 'create')
            ->notEmptyString('deposit');

        return $validator;
    }

    /**
     * @return bool
     */

    public function getWeekAhead(){
        $now = Time::now('Europe/London')->i18nFormat('MMM dd, yyyy');
        $now1 = Time::now('Europe/London')->addDays(1)->i18nFormat('MMM dd, yyyy');
        $now2 = Time::now('Europe/London')->addDays(2)->i18nFormat('MMM dd, yyyy');
        $now3 = Time::now('Europe/London')->addDays(3)->i18nFormat('MMM dd, yyyy');
        $now4 = Time::now('Europe/London')->addDays(4)->i18nFormat('MMM dd, yyyy');
        $now5 = Time::now('Europe/London')->addDays(5)->i18nFormat('MMM dd, yyyy');
        $now6 = Time::now('Europe/London')->addDays(6)->i18nFormat('MMM dd, yyyy');
        $week_ahead = [ $now=>$now, $now1=>$now1, $now2=>$now2, $now3=>$now3, $now4=>$now4, $now5=>$now5, $now6=>$now6 ];
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
        $slots_ahead = [$now,$nowpm,$now1,$now1pm,$now2,$now2pm,$now3,$now3pm,$now4,$now4pm,$now5,$now5pm,$now6,$now6pm];
        return $slots_ahead;
    }
    public function getBAvailability($id){
        // get bookings for the week ahead
        $today_9am = Time::now('Europe/London')->setTime(9, 00);
        $bookingsTable = TableRegistry::getTableLocator()->get('Bookings');
        $bookings_for_bike = $bookingsTable->find()->where(['bike_id'=>$id,'booking_start >='=>$today_9am,'status'=>'BOOKED']);
        $slots_ahead = $this->getSlotsAhead();
        $bookings_start_to_end=[];
        foreach($bookings_for_bike as $booking){
            $bookings_start_to_end[]=[$booking->booking_start,$booking->booking_end];
        }
        $bookings_to_view = [];
        $slots_booked =[];
        $no_of_bookings = count($bookings_start_to_end);
        foreach ($slots_ahead as $slot) {
         for($y = 0; $y < $no_of_bookings; $y++) {
             if ($slot == $bookings_start_to_end[$y][0] || ($slot > $bookings_start_to_end[$y][0] && $slot < $bookings_start_to_end[$y][1])) {
                 array_push($slots_booked, $slot);
                }
            }
        }
        foreach ($slots_ahead as $slot) {
            if(in_array($slot,$slots_booked)){
                array_push($bookings_to_view, 'BOOKED');
            }
            else{
                array_push($bookings_to_view, $slot);
            }
        }

        return $bookings_to_view;
    }
    public function getAvailability($id){
        // get bookings for the week ahead
        $today_9am = Time::now('Europe/London')->setTime(9, 00);
        $bookingsTable = TableRegistry::getTableLocator()->get('Bookings');
        $bookings_for_bike = $bookingsTable->find()->where(['bike_id'=>$id,'booking_start >='=>$today_9am,'status'=>'BOOKED'])->toArray();
        $bookings_ongoing = $bookingsTable->find()->where(['bike_id'=>$id,'booking_start <'=>$today_9am,'booking_end >'=>$today_9am,'status'=>'BOOKED'])->toArray();
        $bookings_for_bike = array_merge($bookings_for_bike, $bookings_ongoing);
        $slots_ahead = $this->getSlotsAhead();
        $bookings_start_to_end=[];
        foreach($bookings_for_bike as $booking){
            $bookings_start_to_end[]=[$booking->booking_start,$booking->booking_end];
        }
        $bookings_to_view = [];
        $slots_booked =[];
        $no_of_bookings = count($bookings_start_to_end);
        foreach ($slots_ahead as $slot) {
            for($y = 0; $y < $no_of_bookings; $y++) {
                if ($slot == $bookings_start_to_end[$y][0] || ($slot > $bookings_start_to_end[$y][0] && $slot < $bookings_start_to_end[$y][1])) {
                    array_push($slots_booked, $slot);
                }
            }
        }
        foreach ($slots_ahead as $slot) {
            if(in_array($slot,$slots_booked)){
                array_push($bookings_to_view, 'BOOKED');
            }
            else{
                array_push($bookings_to_view, $slot);
            }
        }
        return $bookings_to_view;
    }

    public function searchAvailability($search){
        $start = new Time($search["Start_date"] . ' ' . $search["Start_time"]);
        $finish = new Time($search["Finish_time"] . ' ' . $search["Finish_date"]);
        $bookingsTable = TableRegistry::getTableLocator()->get('Bookings');
        $bicyclesTable = TableRegistry::getTableLocator()->get('Bicycles');
        $allbikes = $bicyclesTable->find('all')->toArray();
        $bookings_overlap = $bookingsTable->find('all')->where(['booking_start <' => $start,'booking_end >' => $finish,'status' => 'BOOKED'])->contain(['Bicycles'])->toArray();
        $start_within_bookings = $bookingsTable->find('all')->where(['booking_start >=' => $start, 'booking_end >' => $start,'status' => 'BOOKED'])->contain(['Bicycles'])->toArray();
        $end_within_bookings = $bookingsTable->find('all')->where(['booking_end >' => $start, 'booking_end <=' => $finish,'status' => 'BOOKED'])->contain(['Bicycles'])->toArray();
        $results = array_merge($bookings_overlap,$start_within_bookings,$end_within_bookings);
        $booked_bikes=[];
        foreach($results as $booked_bike){
            $booked_bike->bicycle;
            if (!in_array($booked_bike->bicycle, $booked_bikes))
            {
                $booked_bikes[] = $booked_bike->bicycle;
            }
        }
        $availableBikes = array_diff($allbikes, $booked_bikes);
        return $availableBikes;
    }


    public function searchBAvailability($search){
        $start = new Time($search["Start_date"] . ' ' . $search["Start_time"]);
        $finish = new Time($search["Finish_time"] . ' ' . $search["Finish_date"]);
        $bicyclesTable = TableRegistry::getTableLocator()->get('Bicycles');
        $bookingsTable = TableRegistry::getTableLocator()->get('Bookings');
        $allbikes = $bicyclesTable->find('all');
        $availableBikes = [];
        $test = [];
        foreach($allbikes as $bike){
            $x = $bookingsTable->find()
                ->where(['bike_id'=>$bike->id,'booking_start' => $start,'status' => 'BOOKED']);
            $y = $bookingsTable->find()
                ->where(['bike_id'=>$bike->id,'booking_start >' => $start,'booking_start <' => $finish,'status' => 'BOOKED']);
            if(!($x -> first()) && !($y -> first())){
                $availableBikes [] = $bike;
            }
        }
        //dd($availableBikes);
        return $availableBikes;
    }

    public function getAllAvailability(){
        $bicyclesTable = TableRegistry::getTableLocator()->get('Bicycles');
        $allbikes = $bicyclesTable->find('all');
        $all_bikes_availibility= [];
        foreach($allbikes as $bike) {
            $bike_avail_for_array = [];
            $numbers = [];
            $bike_availability = $this->getAvailability($bike->id);
            for($x=0; $x<13; $x+=2) {
                $numbers [] = $x;
                if (($bike_availability[$x] == "BOOKED") && ($bike_availability[$x+1] == "BOOKED")) {
                    $bike_avail_for_array [] = 'B';
                }
                elseif (($bike_availability[$x] == "BOOKED") || ($bike_availability[$x+1] == "BOOKED") ){
                    $bike_avail_for_array [] = 'P';
                }
                elseif ($bike_availability[$x] != "BOOKED" && $bike_availability[$x+1] != "BOOKED") {
                    $bike_avail_for_array [] = 'A';
                }
            }
            $all_bikes_availibility [$bike->id] = $bike_avail_for_array;
        }
        return $all_bikes_availibility;
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
