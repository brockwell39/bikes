<?php
use Migrations\AbstractMigration;

class AddKeyToInvoices1 extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('invoices1');
        $table->update();
    }
}
