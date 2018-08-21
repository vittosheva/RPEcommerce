<?php

use Illuminate\Database\Seeder;
use App\Models\Billing\InvoiceProofCode;

class InvoiceProofCodeSeeder extends Seeder
{
    use TruncateTable;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->truncate('invoice_proof_codes');

        InvoiceProofCode::create([
            'EDocTypeCode'  => '01',
            'description'   => 'FACTURA',
            'EDocTypeSri'   => '01',
            'status'        => 1
        ]);

        InvoiceProofCode::create([
            'EDocTypeCode'  => '18',
            'description'   => 'ACTUALIZAR',
            'EDocTypeSri'   => '18',
            'status'        => 1
        ]);

        InvoiceProofCode::create([
            'EDocTypeCode'  => '11',
            'description'   => 'PASAJES EXPEDIDOS POR EMPRESAS DE AVIACION',
            'EDocTypeSri'   => '11',
            'status'        => 1
        ]);

        InvoiceProofCode::create([
            'EDocTypeCode'  => '04',
            'description'   => 'NOTA DE CREDITO',
            'EDocTypeSri'   => '04',
            'status'        => 1
        ]);

        InvoiceProofCode::create([
            'EDocTypeCode'  => '05',
            'description'   => 'NOTA DE DEBITO',
            'EDocTypeSri'   => '05',
            'status'        => 1
        ]);

        InvoiceProofCode::create([
            'EDocTypeCode'  => '06',
            'description'   => 'GUIA DE REMISION',
            'EDocTypeSri'   => '06',
            'status'        => 1
        ]);

        InvoiceProofCode::create([
            'EDocTypeCode'  => '07',
            'description'   => 'COMPROBANTE DE RETENCION',
            'EDocTypeSri'   => '05',
            'status'        => 1
        ]);

        InvoiceProofCode::create([
            'EDocTypeCode'  => '02',
            'description'   => 'NOTAS DE VENTAS',
            'EDocTypeSri'   => '02',
            'status'        => 1
        ]);

        InvoiceProofCode::create([
            'EDocTypeCode'  => '20',
            'description'   => 'DOCUMENTOS POR SERVICIOS ADMINISTRATIVOS EMITIDOS POR INST. DEL ESTADO',
            'EDocTypeSri'   => '20',
            'status'        => 1
        ]);

        InvoiceProofCode::create([
            'EDocTypeCode'  => '19',
            'description'   => 'COMPROBANTES DE PAGO DE CUOTAS O APORTES',
            'EDocTypeSri'   => '19',
            'status'        => 1
        ]);

        InvoiceProofCode::create([
            'EDocTypeCode'  => '03',
            'description'   => 'LIQUIDACION DE COMPRA',
            'EDocTypeSri'   => '03',
            'status'        => 1
        ]);
    }
}
