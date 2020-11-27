<?php
use Migrations\AbstractMigration;

class AddOwnerToBookings extends AbstractMigration
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
        $table = $this->table('bookings');
        $table->addColumn('owner_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $this->table('bookings')
            ->addForeignKey(
                'owner_id',
                'users',
                'id',
                [
                    'update' => 'NO_ACTION',
                    'delete' => 'NO_ACTION'
                ]
            );
        $table->update();
    }
}
