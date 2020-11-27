<li class="heading"><?= __('Actions') ?></li>
<li><?= $this->Html->link(__('List Bicycles'), ['controller' => 'Bicycles', 'action' => 'index']) ?></li>
<li><?= $this->Html->link(__('Add Bicycle'), ['controller' => 'Bicycles', 'action' => 'add']) ?></li>
<li><?= $this->Html->link(__('Search'), ['controller' => 'Bicycles', 'action' => 'search']) ?></li>
<li class="heading"><?= __('Bookings') ?></li>
<li><?= $this->Html->link(__('My Bookings'), ['controller' => 'Bookings', 'action' => 'index']) ?></li>
<li><?= $this->Html->link(__('My Invoices'), ['controller' => 'Invoices', 'action' => 'index']) ?></li>
<li><?= $this->Html->link(__('Deposit'), ['controller' => 'Transactions', 'action' => 'deposit']) ?></li>
<li class="heading"><?= __('My Account') ?></li>
<li><?= $this->Html->link(__('My Account'), ['controller' => 'Invoices', 'action' => 'deposits']) ?></li>
<li class="heading"><?= __('Actions') ?></li>
<li><?= $this->Html->link(__('Logout'), ['controller' => 'Users', 'action' => 'logout']) ?></li>
