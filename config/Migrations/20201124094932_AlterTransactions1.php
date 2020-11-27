<?php
use Migrations\AbstractMigration;

class AlterTransactions1 extends AbstractMigration
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
        $table->changeColumn('debit', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->changeColumn('credit', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->update();
    }
}
