<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use http\Client\Curl\User;

/**
 * Transactions Controller
 *
 * @property \App\Model\Table\TransactionsTable $Transactions
 *
 * @method \App\Model\Entity\Transaction[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TransactionsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        // Add the 'add' action to the allowed actions list.
        //$this->Auth->allow(['add','search']);
        $this->Auth->deny(['index','edit','view']);
    }
    public function isAuthorized($user)
    {
        $action = $this->request->getParam('action');
        // The add and tags actions are always allowed to logged in users.
        if (in_array($action, ['add','deposit','index'])) {
            return true;
        }

        // All other actions require a slug.
        $slug = $this->request->getParam('pass.0');
        //dd($slug);
        if (!$slug) {
            return false;
        }
        // Check that the invoice belongs to the current user.
        $bookingsTable = TableRegistry::getTableLocator()->get('Bookings');
        $booking = $bookingsTable->get($slug);

        return $booking->user_id === $user['id'];
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Invoices'],
        ];
        $transactions = $this->paginate($this->Transactions);

        $this->set(compact('transactions'));
    }

    /**
     * View method
     *
     * @param string|null $id Transaction id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $transaction = $this->Transactions->get($id, [
            'contain' => ['Invoices'],
        ]);

        $this->set('transaction', $transaction);
    }
    public function deposit()
    {
        $balance = $this->Transactions->getBalance($this->Auth->user('id'));
        $transaction = $this->Transactions->newEntity();
        if ($this->request->is('post')) {
            $transaction = $this->Transactions->patchEntity($transaction, $this->request->getData());
            $transaction->user = $this->Auth->user('id');
            $transaction->credit = 0;
            $this->Transactions->save($transaction);
            if ($this->Transactions->save($transaction)) {
                $this->Flash->success(__('The deposit was successful.'));
                return $this->redirect(['action' => 'deposit']);
            }
            //dd($transaction->getErrors());
            $this->Flash->error(__('The deposit was unsuccessful. Please, try again.'));
        }
        $this->set(compact('transaction','balance'));
    }
    public function pay($invoice_id){
        $bookingsTable = TableRegistry::getTableLocator()->get('Bookings');
        $booking = $bookingsTable->get($invoice_id,[
            'contain' => ['Invoices']
        ]);
        if($booking->status=='PAID'){
            $this->Flash->success(__('The invoice has already been paid.'));
            return $this->redirect(['controller' => 'invoices']);
        }
        $code = 'P';
        if($this->Transactions->pay($booking,$code)){
            $booking->invoice->status = 'PAID';
            $booking->invoice->deposit_status = 'PAID';
            $booking->setDirty('invoice');
            if($bookingsTable->save($booking)){
                $this->Flash->success(__('The invoice has been paid.'));
                return $this->redirect(['controller' => 'invoices']);
            }
            return false;
        }
    }
    public function pay1($invoice_id){

        $bookingsTable = TableRegistry::getTableLocator()->get('Bookings');
        $invoicesTable = TableRegistry::getTableLocator()->get('Invoices');
        $booking = $bookingsTable->get($invoice_id,[
            'contain' => ['Invoices']
        ]);
        $invoice = $invoicesTable->get($invoice_id);
        if($invoice->status='PAID'){
            $this->Flash->success(__('The invoice has already been paid.'));
            return $this->redirect(['controller' => 'invoices']);
        }

        if($this->Transactions->pay($booking)){
            $booking->status = 'PAID';
            $invoice->status = 'PAID';
            $invoice->deposit_status = 'PAID';
            if($bookingsTable->save($booking))
            {
                if($invoicesTable->save($invoice))
                {
                    $this->Flash->success(__('The invoice has been paid.'));
                    return $this->redirect(['controller' => 'invoices']);
                }
                return false;
            }
            return false;
        }
    }




    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $transaction = $this->Transactions->newEntity();
        if ($this->request->is('post')) {
            $transaction = $this->Transactions->patchEntity($transaction, $this->request->getData());
            if ($this->Transactions->save($transaction)) {
                $this->Flash->success(__('The transaction has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The transaction could not be saved. Please, try again.'));
        }
        $invoices = $this->Transactions->Invoices->find('list', ['limit' => 200]);
        $this->set(compact('transaction', 'invoices'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Transaction id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $transaction = $this->Transactions->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $transaction = $this->Transactions->patchEntity($transaction, $this->request->getData());
            if ($this->Transactions->save($transaction)) {
                $this->Flash->success(__('The transaction has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The transaction could not be saved. Please, try again.'));
        }
        $invoices = $this->Transactions->Invoices->find('list', ['limit' => 200]);
        $this->set(compact('transaction', 'invoices'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Transaction id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $transaction = $this->Transactions->get($id);
        if ($this->Transactions->delete($transaction)) {
            $this->Flash->success(__('The transaction has been deleted.'));
        } else {
            $this->Flash->error(__('The transaction could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
