<?php

use Illuminate\Database\Seeder;
use App\Models\Billing\IdentificationType;

class IdentificationTypeSeeder extends Seeder
{
	use TruncateTable;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->truncate('identification_type');

        IdentificationType::create([
            'description'  	=> 'RUC',
            'code'   		=> '04',
            'requirement'   => 1
        ]);

        IdentificationType::create([
            'description'  	=> 'CEDULA',
            'code'   		=> '05',
            'requirement'   => 1
        ]);

        IdentificationType::create([
            'description'  	=> 'PASAPORTE',
            'code'   		=> '06',
            'requirement'   => 1
        ]);

        IdentificationType::create([
            'description'  	=> 'VENTA A CONSUMIDOR FINAL*',
            'code'   		=> '07',
            'requirement'   => 1
        ]);

        IdentificationType::create([
            'description'  	=> 'IDENTIFICACION DELEXTERIOR*',
            'code'   		=> '08',
            'requirement'   => 1
        ]);

        IdentificationType::create([
            'description'  	=> 'PLACA',
            'code'   		=> '09',
            'requirement'   => 1
        ]);

    }
}
