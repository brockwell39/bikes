<?php
namespace App\Model\Table;

use App\Model\Entity\Booking;
use Cake\I18n\Time;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

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

    public function makeBooking($bike_id,$bookingCode,$user)
    {

        $bookingsTable = TableRegistry::getTableLocator()->get('Bookings');
        $booking = new Booking;
        $booking->user_id = $user;
        $booking->bike_id = $bike_id;
        $bookings_to_view = $this->Bicycles->getAvailibility($bike_id);
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
            return true;
        }
        return false;
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
