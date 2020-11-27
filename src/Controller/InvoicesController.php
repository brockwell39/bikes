<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Invoices Controller
 *
 * @property \App\Model\Table\InvoicesTable $Invoices
 *
 * @method \App\Model\Entity\Invoice[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class InvoicesController extends AppController
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
        if (in_array($action, ['add','tags','index','book','deposits'])) {
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
           'contain' => ['Bookings'],
           'conditions' => ['Bookings.user_id' => $this->Auth->user('id')]
        ];

        $bookedInvoices = $this->paginate(
            $this->Invoices->find('all')->where(['Invoices.status'=>'BOOKED'])
        );
        $paidInvoices = $this->paginate(
            $this->Invoices->find('all')->where(['Invoices.status'=>'PAID'])
        );

        $transactionsTable = TableRegistry::getTableLocator()->get('Transactions');
        $balance = $transactionsTable->getBalance($this->Auth->user('id'));
        $this->set(compact('bookedInvoices','balance','paidInvoices'));
    }

    /**
     * View method
     *
     * @param string|null $id Invoice id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function deposits($id = null)
    {
        $this->paginate = [
        'contain' => ['Bookings'],
        'conditions' => ['Bookings.user_id' => $this->Auth->user('id')]
    ];

        $bookedInvoices = $this->paginate(
            $this->Invoices->find('all')->where(['Invoices.status'=>'BOOKED'])
        );
        $paidInvoices = $this->paginate(
            $this->Invoices->find('all')->where(['Invoices.status'=>'PAID'])
        );

        $transactionsTable = TableRegistry::getTableLocator()->get('Transactions');
        $balance = $transactionsTable->getBalance($this->Auth->user('id'));
        $this->set(compact('bookedInvoices','balance','paidInvoices'));
    }

    public function returnDeposit($booking_id=null)
    {
        $bookingsTable = TableRegistry::getTableLocator()->get('Bookings');
        $booking = $bookingsTable->get($booking_id,['contain' => ['Invoices']]);
        if($this->Invoices->returnDeposit($booking)){
            $this->Flash->success(__('The deposit has been returned.'));
            return $this->redirect(['action' => 'deposits']);
       }
        $this->Flash->success(__('The deposit has already been returned.'));
        return $this->redirect(['action' => 'deposits']);
    }


    public function view($id = null)
    {
        $invoice = $this->Invoices->get($id, [
            'contain' => ['Bookings'],
            //'conditions' => ['Bookings.user_id' => $this->Auth->user('id')]
        ]);

        $this->set('invoice', $invoice);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $invoice = $this->Invoices->newEntity();
        if ($this->request->is('post')) {
            $invoice = $this->Invoices->patchEntity($invoice, $this->request->getData());
            if ($this->Invoices->save($invoice)) {
                $this->Flash->success(__('The invoice has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The invoice could not be saved. Please, try again.'));
        }
        $bookings = $this->Invoices->Bookings->find('list', ['limit' => 200]);
        $this->set(compact('invoice', 'bookings'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Invoice id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $invoice = $this->Invoices->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $invoice = $this->Invoices->patchEntity($invoice, $this->request->getData());
            if ($this->Invoices->save($invoice)) {
                $this->Flash->success(__('The invoice has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The invoice could not be saved. Please, try again.'));
        }
        $bookings = $this->Invoices->Bookings->find('list', ['limit' => 200]);
        $this->set(compact('invoice', 'bookings'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Invoice id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $invoice = $this->Invoices->get($id);
        if ($this->Invoices->delete($invoice)) {
            $this->Flash->success(__('The invoice has been deleted.'));
        } else {
            $this->Flash->error(__('The invoice could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
