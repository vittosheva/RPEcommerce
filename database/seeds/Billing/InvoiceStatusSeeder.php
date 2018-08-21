<?php

use Illuminate\Database\Seeder;
use App\Models\Billing\InvoiceStatus;

class InvoiceStatusSeeder extends Seeder
{
    use TruncateTable;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->truncate('invoice_statuses');

        InvoiceStatus::create([
            'name'          => 'NO PROCESADA',
            'description'   => 'El Servicio aún no procesa el documento.',
            'status'        => 1
        ]);

        InvoiceStatus::create([
            'name'          => 'RECIBIDA',
            'description'   => 'El Documento está siendo Procesado.',
            'status'        => 1
        ]);

        InvoiceStatus::create([
            'name'          => 'DEVUELTA',
            'description'   => 'El documento fue devuelto por el SRI.',
            'status'        => 1
        ]);

        InvoiceStatus::create([
            'name'          => 'AUTORIZADA',
            'description'   => 'El documento fue autorizado.',
            'status'        => 1
        ]);

        InvoiceStatus::create([
            'name'          => 'NOAUTORIZADA',
            'description'   => 'El documento No fue autorizado por el SRI.',
            'status'        => 1
        ]);

        InvoiceStatus::create([
            'name'          => 'ANULADOSRI',
            'description'   => 'Documento Anulado por el SRI.',
            'status'        => 1
        ]);

        InvoiceStatus::create([
            'name'          => 'CANCELADO',
            'description'   => 'Documento Cancelado por el Servicio, no fue procesado. Error en información.',
            'status'        => 1
        ]);

        InvoiceStatus::create([
            'name'          => 'BATCH',
            'description'   => 'Batch.',
            'status'        => 1
        ]);
    }
}
