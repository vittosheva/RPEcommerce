<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    use TruncateTable;

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->truncateMultiple([
            'cache',
            'jobs',
            'sessions',
        ]);

        $this->call(AuthTableSeeder::class);

        // New
        $this->call(InvoiceStatusSeeder::class);
        $this->call(InvoiceProofCodeSeeder::class);
        $this->call(IdentificationTypeSeeder::class);

        Model::reguard();
    }
}
