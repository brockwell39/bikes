<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Invoices Model
 *
 * @method \App\Model\Entity\Invoice get($primaryKey, $options = [])
 * @method \App\Model\Entity\Invoice newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Invoice[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Invoice|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Invoice saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Invoice patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Invoice[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Invoice findOrCreate($search, callable $callback = null, $options = [])
 */
class InvoicesTable extends Table
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

        $this->setTable('invoices');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->belongsTo('Bookings', [
            'foreignKey' => 'id',
            'joinType' => 'INNER',
        ]);
    }

    public function cancelInvoice($id){
        $invoicesTable = TableRegistry::getTableLocator()->get('Invoices');
        $invoice = $invoicesTable->get($id);
        $invoice->status='CANCELLED';
        if($invoicesTable->save($invoice)){
            return true;
        }
        return false;

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
            ->scalar('status')
            ->maxLength('status', 255)
            ->requirePresence('status', 'create')
            ->notEmptyString('status');

        $validator
            ->integer('weekday_amount')
            ->requirePresence('weekday_amount', 'create')
            ->notEmptyString('weekday_amount');

        $validator
            ->scalar('weekday_quantity')
            ->maxLength('weekday_quantity', 255)
            ->requirePresence('weekday_quantity', 'create')
            ->notEmptyString('weekday_quantity');

        $validator
            ->integer('weekend_amount')
            ->requirePresence('weekend_amount', 'create')
            ->notEmptyString('weekend_amount');

        $validator
            ->integer('weekend_quantity')
            ->requirePresence('weekend_quantity', 'create')
            ->notEmptyString('weekend_quantity');

        $validator
            ->integer('deposit')
            ->requirePresence('deposit', 'create')
            ->notEmptyString('deposit');

        $validator
            ->scalar('deposit_status')
            ->maxLength('deposit_status', 255)
            ->requirePresence('deposit_status', 'create')
            ->notEmptyString('deposit_status');

        $validator
            ->integer('disputed_amount')
            ->requirePresence('disputed_amount', 'create')
            ->notEmptyString('disputed_amount');

        $validator
            ->scalar('dispute_status')
            ->maxLength('dispute_status', 255)
            ->requirePresence('dispute_status', 'create')
            ->notEmptyString('dispute_status');

        return $validator;
    }
}
