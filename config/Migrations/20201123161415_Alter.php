<?php
use Migrations\AbstractMigration;

class Alter extends AbstractMigration
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
        $table = $this->table('Transactions');
        $table->changeColumn('Debit', 'integer', [
            'default' => 0,
        ]);
        $table->changeColumn('Credit', 'integer', [
            'default' => 0,
        ]);
        $table->update();
    }
}

