<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use App\Model\Entity\Booking;
use App\Model\Table\Bookings;

/**
 * Bicycles Controller
 *
 * @property \App\Model\Table\BicyclesTable $Bicycles
 *
 * @method \App\Model\Entity\Bicycle[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class BicyclesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function isAuthorized($user)
    {
        $action = $this->request->getParam('action');
        // The add and tags actions are always allowed to logged in users.
        if (in_array($action, ['add', 'tags','book','search'])) {
            return true;
        }

        // All other actions require a slug.
        $slug = $this->request->getParam('pass.0');
        if (!$slug) {
            return false;
        }

        // Check that the bicycle belongs to the current user.
        $bicycle = $this->Bicycles->get($slug);

        return $bicycle->user_id === $user['id'];
    }

    public function index()
    {
        $this->paginate = [
            'contain' => ['Users'],
        ];
        $bicycles = $this->paginate($this->Bicycles);
        $this->set(compact('bicycles'));
    }
    public function search()
    {
        $week_ahead = $this->Bicycles->getWeekAhead();
        $this->set(compact('week_ahead'));
        $search_results = null;
        $search_data = $this->request->getData();
        if($this->request->getData()){
            $search_results = $this->Bicycles->searchAvailability($this->request->getData());
        }
        $this->set(compact('search_results'));
        $this->set(compact('search_data'));

    }


    /**
     * View method
     *
     * @param string|null $id Bicycle id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $week_ahead = $this->Bicycles->getWeekAhead();
        $this->set(compact('week_ahead'));
        $bookings_to_view = $this->Bicycles->getAvailability($id);
        $this->set(compact('bookings_to_view'));
        $slots_ahead = $this->Bicycles->getSlotsAhead();
        $this->set(compact('slots_ahead'));


        $bicycle = $this->Bicycles->get($id, [
            'contain' => ['Users'],
        ]);

        $this->set('bicycle', $bicycle);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $bicycle = $this->Bicycles->newEntity();
        if ($this->request->is('post')) {
            $bicycle = $this->Bicycles->patchEntity($bicycle, $this->request->getData());
            // Changed: Set the user_id from the session.
            $bicycle->user_id = $this->Auth->user('id');

            if ($this->Bicycles->save($bicycle)) {
                $this->Flash->success(__('The bicycle has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The bicycle could not be saved. Please, try again.'));
        }
        $users = $this->Bicycles->Users->find('list', ['limit' => 200]);
        $this->set(compact('bicycle', 'users'));
    }
    public function book($id = null,$bookingCode){
        $user = $this->Auth->user('id');
        $bookingsTable = TableRegistry::getTableLocator()->get('Bookings');
        $booking = new Booking;
        $booking->user_id = $user;
        $booking->bike_id = $id;
        $bookings_to_view = $this->Bicycles->getAvailability($id);
        if($bookings_to_view[$bookingCode]=='BOOKED'){
            $this->Flash->error(__('The booking could not be saved. Please, try again.'));
            return $this->redirect(['action' => 'view',$id]);
        }
        $booking->created = Time::now('Europe/London');
        $start = $bookings_to_view[$bookingCode];
        $end = new Time($bookings_to_view[$bookingCode]);
        $booking->booking_start = $start;
        $booking->booking_end = $end -> addHours(3);
        $booking->status = 'BOOKED';
        //dd($booking);
        if($bookingsTable->save($booking)){
            $this->Flash->success(__('The booking has been saved.'));
            return $this->redirect(['action' => 'view',$id]);
        }
        $this->Flash->error(__('The booking could not be saved. Please, try again.'));
        return $this->redirect(['action' => 'view',$id]);
    }

    /**
     * Edit method
     *
     * @param string|null $id Bicycle id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $bicycle = $this->Bicycles->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $bicycle = $this->Bicycles->patchEntity($bicycle, $this->request->getData());
            if ($this->Bicycles->save($bicycle)) {
                $this->Flash->success(__('The bicycle has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The bicycle could not be saved. Please, try again.'));
        }
        $users = $this->Bicycles->Users->find('list', ['limit' => 200]);
        $this->set(compact('bicycle', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Bicycle id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $bicycle = $this->Bicycles->get($id);
        if ($this->Bicycles->delete($bicycle)) {
            $this->Flash->success(__('The bicycle has been deleted.'));
        } else {
            $this->Flash->error(__('The bicycle could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
