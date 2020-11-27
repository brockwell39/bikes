<?php
use Migrations\AbstractMigration;

class AlterTransactions extends AbstractMigration
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
        $table = $this->table('transactions');
        $table->changeColumn('Debit', 'integer', [
            'default' => null,
        ]);
        $table->changeColumn('Credit', 'integer', [
            'default' => null,
        ]);
        $table->update();
    }
}
