<?php
namespace App\Model\Table;

use App\Model\Entity\Booking;
use App\Model\Entity\Invoice;
use Cake\I18n\Time;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Chronos\Chronos;

/**
 * Bookings Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\BicyclesTable&\Cake\ORM\Association\BelongsTo $Bicycles
 *
 * @method \App\Model\Entity\Booking get($primaryKey, $options = [])
 * @method \App\Model\Entity\Booking newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Booking[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Booking|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Booking saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Booking patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Booking[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Booking findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BookingsTable extends Table
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

        $this->setTable('bookings');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');


        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Bicycles', [
            'foreignKey' => 'bike_id',
            'joinType' => 'INNER',
        ]);
        $this->hasOne('Invoices', [
            'foreignKey' => 'id',
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
            ->dateTime('booking_start')
            ->allowEmptyDateTime('booking_start');

        $validator
            ->dateTime('booking_end')
            ->allowEmptyDateTime('booking_end');

        $validator
            ->scalar('status')
            ->maxLength('status', 10)
            ->allowEmptyString('status');

        return $validator;
    }
    public function createInvoice($booking_id){
        $bookingsTable = TableRegistry::getTableLocator()->get('Bookings');
        $invoiceTable = TableRegistry::getTableLocator()->get('Invoices');
        $booking = $bookingsTable->get($booking_id,['contain'=>'Bicycles']);
        $booking_length = $this->getSlots($booking->booking_start,$booking->booking_end);
        $invoice = new Invoice;
        $invoice->id = $booking_id;
        $invoice->status = 'BOOKED';
        $invoice->weekday_amount = $booking->bicycle->weekday_price;
        $invoice->weekend_amount = $booking->bicycle->weekend_price;
        $booking->booking_start;
        $time = new Time($booking->booking_start);
        $weekday_count = 0;
        $weekend_count = 0;
        //Works out how many weekend and weekday booking slots are taken by booking
        for($x = 0; $x < $booking_length; $x++) {
            $day = new Time($time);
            $am_pm = new Time($time);
            $day = $day->format('D');
            $am_pm = $am_pm->format('A');
            if ($day == 'Sat' || $day == 'Sun') {
                $weekend_count += 1;
            } else {
                $weekday_count += 1;
            }
            if($am_pm == 'AM'){
                $time->modify('+4 hours');
            }
            else{
                $time->modify('+20 hours');
            }
        }
        $invoice->deposit = $booking->bicycle->deposit;
        $invoice->weekday_quantity = $weekday_count;
        $invoice->weekend_quantity = $weekend_count;
        $invoice->deposit_status = 'UNPAID';
        $invoice->disputed_amount = 0;
        $invoice->dispute_status = 'NONE';
        if ($invoiceTable->save($invoice)) {
            return true;
        }
        return false;

    }
    public function cancelInvoice($id){
        $invoicesTable = TableRegistry::getTableLocator()->get('Invoices');
        $bookingsTable = TableRegistry::getTableLocator()->get('Bookings');
        $invoice = $invoicesTable->get($id);
        $invoice->status='CANCELLED';
        if($invoicesTable->save($invoice)){
            return true;
        }
        return false;

    }

    public function makeBooking($bike_id,$bookingCode,$user)
    {
        $bookingsTable = TableRegistry::getTableLocator()->get('Bookings');
        $booking = new Booking;
        $booking->user_id = $user;
        $booking->bike_id = $bike_id;
        //$bookings_to_view = $this->Bicycles->getbAvailability();
        $bookings_to_view = $this->Bicycles->getAvailability($bike_id);
        if ($bookings_to_view[$bookingCode] == 'BOOKED') {
            return false;
        }
        $booking->created = Time::now('Europe/London');
        $start = $bookings_to_view[$bookingCode];
        $end = new Time($bookings_to_view[$bookingCode]);
        $booking->booking_start = $start;
        $booking->booking_end = $end->addHours(3);
        $booking->status = 'BOOKED';
        if ($bookingsTable->save($booking)) {
            if($this->createInvoice($booking->id)){
                return true;
            }
            return false;
        }
        return false;
    }


    public function getSlots($start,$finish){
        $interval = $start->diff($finish);
        $slots = 0;
        if ($interval->h == 3) {
            $slots =+ 1;
        } elseif ($interval->h == 7) {
            $slots =+ 2;
        }
        $slots = $slots + (($interval->d) * 2);
        return $slots;
    }

    public function makeBulkBooking($bulk_booking,$user)
    {
        $bookings_of_bike = $this->Bicycles->getAvailability($bulk_booking["Bike"]);
        $start = new Time($bulk_booking["Start_date"] . ' ' . $bulk_booking["Start_time"]);
        $finish = new Time($bulk_booking["Finish_time"] . ' ' . $bulk_booking["Finish_date"]);
        if($finish < $start){
            return false;
        }
        $slots = $this->getSlots($start,$finish);
        $array_position = array_search($start, $bookings_of_bike);
        if ($array_position !== false) {
            $booking_end = $array_position + $slots;
                for ($array_position; $array_position < $booking_end; $array_position++) {
                    if ($bookings_of_bike[$array_position] == 'BOOKED') {
                        return false;
                    }
                    $bookingsTable = TableRegistry::getTableLocator()->get('Bookings');
                    $booking = new Booking;
                    $booking->user_id = $user;
                    $booking->bike_id = $bulk_booking["Bike"];
                    $booking->created = Time::now('Europe/London');
                    $booking->booking_start = $start;
                    $booking->booking_end = $finish;
                    $booking->status = 'BOOKED';
                    if ($bookingsTable->save($booking)) {
                        if($this->createInvoice($booking->id)){
                            return true;
                        }
                    }
                    return false;
                }
                return false;
            }
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
        $rules->add($rules->existsIn(['bike_id'], 'Bicycles'));

        return $rules;
    }
}
