<?php
use Migrations\AbstractMigration;

class AddExtraToBicycles extends AbstractMigration
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
        $table = $this->table('bicycles');
        $table->addColumn('deposit', 'integer', [
            'default' => null,
            'limit' => 5,
            'null' => false,
        ]);
        $table->update();
    }
}
