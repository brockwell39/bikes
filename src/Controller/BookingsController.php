<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Bookings Controller
 *
 * @property \App\Model\Table\BookingsTable $Bookings
 *
 * @method \App\Model\Entity\Booking[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class BookingsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        // Add the 'add' action to the allowed actions list.
        $this->Auth->allow(['add']);
        $this->Auth->deny(['index','edit']);
    }
    public function isAuthorized($user)
    {
        $action = $this->request->getParam('action');
        // The add and tags actions are always allowed to logged in users.
        if (in_array($action, ['add','tags','index','book'])) {
            return true;
        }

        // All other actions require a slug.
        $slug = $this->request->getParam('pass.0');
        if (!$slug) {
            return false;
        }

        // Check that the article belongs to the current user.
        $article = $this->Bookings->get($slug);

        return $article->user_id === $user['id'];
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $user = $this->Auth->user('id');

        $this->paginate = [
            'contain' => ['Users','Bicycles'],
        ];
        //$books = $this->Bookings->find()->where(['user_id' => $user]);
        //$this->set('bookings', $this->paginate($books));

        //$data = $books->toArray();
        //dd($data);
        //$this->Bookings->where(['id' => $id])
        //$bookings = $this->paginate($books);
        //$test = $this->Bookings->find()->where(['user_id' => $user]);
        //dd($test);
        $bookings = $this->paginate($this->Bookings);

        $this->set(compact('bookings'));

        //$query = $this->Articles->find('published');

        //dd($bookings);

        $this->set(compact('bookings'));
    }

    /**
     * View method
     *
     * @param string|null $id Booking id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $booking = $this->Bookings->get($id, [
            'contain' => ['Users', 'Bicycles'],
        ]);

        $this->set('booking', $booking);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $booking = $this->Bookings->newEntity();
        if ($this->request->is('post')) {
            $booking = $this->Bookings->patchEntity($booking, $this->request->getData());
            if ($this->Bookings->save($booking)) {
                $this->Flash->success(__('The booking has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The booking could not be saved. Please, try again.'));
        }
        $users = $this->Bookings->Users->find('list', ['limit' => 200]);
        $bikes = $this->Bookings->Bikes->find('list', ['limit' => 200]);
        $this->set(compact('booking', 'users', 'bikes'));
    }

    public function book($bike_id = null,$bookingCode){
        $user = $this->Auth->user('id');
        if($this->Bookings->makeBooking($bike_id,$bookingCode,$user)){
            $this->Flash->success(__('The booking has been saved.'));
            return $this->redirect(['controller'=>'bicycles','action' => 'view',$bike_id]);
        };
        $this->Flash->error(__('The booking could not be saved. Please, try again.'));
        return $this->redirect(['controller'=>'bicycles','action' => 'view',$bike_id]);

    }

    public function cancel($id=null){
        $booking = $this->Bookings->get($id);
        $booking->status='CANCELLED';
        if($this->Bookings->save($booking)){
            $this->Flash->success(__('The booking has been cancelled'));
            return $this->redirect(['action'=>'view',$id]);
        }
        $this->Flash->error(__('The booking could not be saved. Please, try again.'));
        return $this->redirect(['action' => 'view',$id]);

    }
    /**
     * @param string|null $viewClass
     */


    /**
     * Edit method
     *
     * @param string|null $id Booking id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
 /*   public function edit($id = null)
    {
        $booking = $this->Bookings->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $booking = $this->Bookings->patchEntity($booking, $this->request->getData());
            if ($this->Bookings->save($booking)) {
                $this->Flash->success(__('The booking has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The booking could not be saved. Please, try again.'));
        }
        $users = $this->Bookings->Users->find('list', ['limit' => 200]);
        $bicycles = $this->Bookings->Bicycles->find('list', ['limit' => 200]);
        $this->set(compact('booking', 'users', 'bicycles'));
    }*/

    /**
     * Delete method
     *
     * @param string|null $id Booking id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $booking = $this->Bookings->get($id);
        if ($this->Bookings->delete($booking)) {
            $this->Flash->success(__('The booking has been deleted.'));
        } else {
            $this->Flash->error(__('The booking could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
