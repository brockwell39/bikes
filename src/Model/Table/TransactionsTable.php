<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Transactions Model
 *
 * @property \App\Model\Table\InvoicesTable&\Cake\ORM\Association\BelongsTo $Invoices
 *
 * @method \App\Model\Entity\Transaction get($primaryKey, $options = [])
 * @method \App\Model\Entity\Transaction newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Transaction[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Transaction|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Transaction saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Transaction patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Transaction[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Transaction findOrCreate($search, callable $callback = null, $options = [])
 */
class TransactionsTable extends Table
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

        $this->setTable('transactions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');


        $this->belongsTo('Invoices', [
            'foreignKey' => 'invoice_id',
            'joinType' => 'INNER',
        ]);
    }

    public function getBalance($id)
    {
        $transactions = $this->find()->where(['user'=>$id]);
        $debit = $transactions->sumOf('debit');
        $credit = $transactions->sumOf('credit');
        return $debit - $credit;
    }
    public function pay1($booking)
    {
        $user_id = $booking->user_id;
        $owner_id = $booking->owner_id;
        $total = $booking->invoice->invoice_total;
        $credit = $this->newEntity();
        $credit->user = $user_id;
        $credit->transaction_type = "Payment";
        $credit->credit = $total;
        $credit->debit = 0;
        $credit->invoice_id = $booking->id;
        $debit = $this->newEntity();
        $debit->user = $owner_id;
        $debit->transaction_type = "Receipt";
        $debit->debit = $total;
        $debit->credit = 0;
        $debit->invoice_id = $booking->id;
        $data = [$credit,$debit];
        if($this->saveMany($data)){
                return true;
        }
        return false;
    }
    public function pay($booking,$code)
    {
        if($code == 'D'){
            $credit_code = 'Deposit Return';
            $debit_code = 'Deposit Return';
            $amount = $booking->invoice->deposit;
        }
        elseif($code == 'P'){
            $credit_code = 'Payment';
            $debit_code = 'Receipt';
            $amount = $booking->invoice->invoice_total;
        }
        $user_id = $booking->user_id;
        $owner_id = $booking->owner_id;
        $credit = $this->newEntity();
        $credit->user = $user_id;
        $credit->transaction_type = $credit_code;
        $credit->credit = $amount;
        $credit->debit = 0;
        $credit->invoice_id = $booking->id;
        $debit = $this->newEntity();
        $debit->user = $owner_id;
        $debit->transaction_type = $debit_code;
        $debit->debit = $amount;
        $debit->credit = 0;
        $debit->invoice_id = $booking->id;
        $data = [$credit,$debit];
        if($this->saveMany($data)){
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
            ->integer('user')
            ->requirePresence('user', 'create')
            ->notEmptyString('user');

        $validator
            ->integer('debit')
            ->requirePresence('debit', 'create')
            ->notEmptyString('debit');

        $validator
            ->integer('credit')
            ->requirePresence('credit', 'create')
            ->notEmptyString('credit');

        $validator
            ->scalar('transaction_type')
            ->maxLength('transaction_type', 255)
            ->requirePresence('transaction_type', 'create')
            ->notEmptyString('transaction_type');

        $validator
            ->dateTime('datetime')
            ->allowEmptyDateTime('datetime');

        return $validator;
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
        $rules->add($rules->existsIn(['invoice_id'], 'Invoices'));

        return $rules;
    }
}
