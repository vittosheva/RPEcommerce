<?php

namespace App\Http\Controllers\Backend\Billing;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Backend\WebService\WebServiceController;
use Spatie\ArrayToXml\ArrayToXml;
use Illuminate\Support\Carbon;

class InvoiceController extends Controller
{
    protected $wsdl_url         = 'http://redfactura.ec:8282/Pruebas/KEB_Empresas/operations.asmx?WSDL';
    protected $user             = 'PortalRedpagos';
    protected $pass             = 'R3dPaG0s20183C0mm3rc3';
    protected $userEntityId     = '0E880886-BEF6-411D-A38E-3FA99B2E40CF';
    protected $razonSocial      = 'REDPAGOS S.A.';
    protected $nombreComercial  = 'REDPAGOS S.A.';
    protected $ruc              = '0992724641001';
    protected $claveAcceso      = '0000000000000000000000000000000000000000000000000';
    protected $codDoc           = '01';
    protected $estab            = '009';
    protected $ptoEmi           = '001';
    protected $secuencial       = '';
    protected $dirMatriz        = 'CDLA. LA GARZOTA I MZ. 5 SOLAR 13';

    /**
     * Billing Process before webservice consume
     *
     * @param $collection
     * @return mixed|string
     */
    public function process($collection)
    {
    	// Multiple data info
        $clientContact	= $this->clientConnectObject($collection);       // Client Connect Object
        $xml        	= $this->generateXml($collection);              // XML

        // Web Service
    	$webservice 	= new WebServiceController();
    	$responseData	= $webservice->AddFilesWithApp($xml, $clientContact);

    	if ($responseData === null || !isset($responseData->AddFilesWithAppResult) || !isset($responseData->AddFilesWithAppResult->Processed) || $responseData->AddFilesWithAppResult->Processed == 0) {
            $getData = "Error al momento de consumir el servicio.";
    	} else {
    		$getData = $webservice->GetData($responseData->AddFilesWithAppResult->DocumentId);
    	}

        return $getData;
    }

    /**
     * Client Contact Object
     *
     * @param null $clientInfo
     * @return \stdClass
     */
    public function clientConnectObject($clientInfo)
    {
    	// Create empty object
        $clientContact = new \stdClass();

        $clientContact->Name        = $clientInfo->fullname;
        $clientContact->Email1      = $clientInfo->email;
        $clientContact->EmailCC1    = $clientInfo->email;
        $clientContact->Cellphone1  = ($clientInfo->phone_mobile != '') ? $clientInfo->phone_mobile : $clientInfo->phone;
        $clientContact->UrlPost     = env('APP_URL');

        return $clientContact;
    }

    /**
     * Generate bills' XML
     *
     * @param null $billingInfo
     * @return string
     */
    public function generateXml($billingInfo)
    {
        if (isset($billingInfo->orderDetails) && count($billingInfo->orderDetails) > 0) {
            foreach ($billingInfo->orderDetails as $detail) {
                $details []= [
                    'detalle'                       => [
                        'codigoPrincipal'           => $detail->product_id,
                        'descripcion'               => $detail->product_name,
                        'cantidad'                  => $detail->product_quantity,
                        'precioUnitario'            => $detail->unit_price_tax_excl,
                        'descuento'                 => '0.00',
                        'precioTotalSinImpuesto'    => $detail->total_price_tax_excl,
                        'impuestos'                 => [
                            'impuesto'      => [
                                'codigo'            => '2',
                                'codigoPorcentaje'  => '2',
                                'tarifa'            => '12',
                                'baseImponible'     => $detail->total_price_tax_excl,
                                'valor'             => $detail->total_price_tax_excl - $detail->total_price_tax_incl
                            ]
                        ]
                    ]
                ];
            }
        } else {
            $details = [
                'detalle' => []
            ];
        }

        $xml = [
            // INFORMACIÓN TRIBUTARIA
            'infoTributaria'    => [
                'ambiente'                      => 1,
                'tipoEmision'                   => 1,
                'razonSocial'                   => $this->razonSocial,
                'nombreComercial'               => $this->nombreComercial,
                'ruc'                           => $this->ruc,
                'claveAcceso'                   => $this->claveAcceso,
                'codDoc'                        => $this->codDoc,
                'estab'                         => $this->estab,
                'ptoEmi'                        => $this->ptoEmi,
                'secuencial'                    => $this->secuencial,
                'dirMatriz'                     => $this->dirMatriz
            ],

            // INFORMACIÓN FACTURA
            'infoFactura'       => [
                'fechaEmision'                  => Carbon::parse($billingInfo->invoice_date)->format('d/m/Y'),
                'dirEstablecimiento'            => $this->dirMatriz,
                //'contribuyenteEspecial'         => '',
                'obligadoContabilidad'          => 'SI',
                'tipoIdentificacionComprador'   => '05',                                    // Cédula
                'razonSocialComprador'          => $billingInfo->fullname,
                'identificacionComprador'       => $billingInfo->ced_ruc,
                'totalSinImpuestos'             => $billingInfo->total_paid_tax_excl,
                'totalDescuento'                => '0.00',
                'totalConImpuestos'             => [
                    'totalImpuesto'     => [
                        'codigo'            => '2',
                        'codigoPorcentaje'  => '2',
                        'baseImponible'     => $billingInfo->total_paid_tax_excl,
                        'valor'             => $billingInfo->tax,
                    ]
                ],
                'propina'                       => '0.00',
                'importeTotal'                  => $billingInfo->total_paid_tax_incl,
                'moneda'                        => 'DOLAR',
                'pagos'                         => [
                    'pago'              => [
                        'formaPago'         => '01',                                        // Efectivo
                        'total'             => $billingInfo->total_paid_tax_incl
                    ]
                ],
            ],

            // INFORMACIÓN DETALLE
            'detalles'       => [
                $details
            ],

            // INFORMACIÓN ADICIONAL
            'infoAdicional'     => [
                'campoAdicional' => [
                    '_attributes'   => [
                        'nombre' => 'Direccion'
                    ],
                    'value'         => $billingInfo->address1 . ($billingInfo->address2 != '') ? ' '. $billingInfo->address2 : ''
                ],
                'campoAdicional' => [
                    '_attributes'   => [
                        'nombre' => 'Email'
                    ],
                    'value'         => $billingInfo->email
                ]
            ]
        ];

        $root = [
            'rootElementName' => 'factura',
            '_attributes' => [
                'id'        => 'comprobante',
                'version'   => '1.0.0'
            ]
        ];

        $result = ArrayToXml::convert($xml, $root, true, 'UTF-8', '1.0');
        dd($result);

        return $result;
    }
}

/*
<?xml version="1.0" encoding="UTF-8"?>
<factura id="comprobante" version="1.0.0">
  <infoTributaria>
    <ambiente>1</ambiente>
    <tipoEmision>1</tipoEmision>
    <razonSocial>REDPAGOS S.A.</razonSocial>
    <nombreComercial>REDPAGOS S.A.</nombreComercial>
    <ruc>0992724641001</ruc>
    <claveAcceso>0000000000000000000000000000000000000000000000000</claveAcceso>
    <codDoc>01</codDoc>
    <estab>009</estab>
    <ptoEmi>001</ptoEmi>
    <secuencial>000047201</secuencial>
    <dirMatriz>CDLA. LA GARZOTA I MZ. 5 SOLAR 13</dirMatriz>
  </infoTributaria>
  <infoFactura>
    <fechaEmision>09/07/2018</fechaEmision>
    <dirEstablecimiento>CDLA. LA GARZOTA I MZ. 5 SOLAR 13</dirEstablecimiento>
    <contribuyenteEspecial>5368</contribuyenteEspecial>
    <obligadoContabilidad>SI</obligadoContabilidad>
    <tipoIdentificacionComprador>05</tipoIdentificacionComprador>
    <razonSocialComprador>LOOR VELEZ IVON LOURDES</razonSocialComprador>
    <identificacionComprador>1313056721</identificacionComprador>
    <totalSinImpuestos>13.38</totalSinImpuestos>
    <totalDescuento>0.00</totalDescuento>
    <totalConImpuestos>
      <totalImpuesto>
        <codigo>2</codigo>
        <codigoPorcentaje>2</codigoPorcentaje>
        <baseImponible>13.38</baseImponible>
        <valor>1.61</valor>
      </totalImpuesto>
    </totalConImpuestos>
    <propina>0.00</propina>
    <importeTotal>14.99</importeTotal>
    <moneda>DOLAR</moneda>
    <pagos>
      <pago>
        <formaPago>01</formaPago>
        <total>14.99</total>
      </pago>
    </pagos>
  </infoFactura>
  <detalles>
    <detalle>
      <codigoPrincipal>2171182-4-4</codigoPrincipal>
      <descripcion>BERMUDA TALLA 4 ANOS COLOR ROJO</descripcion>
      <cantidad>1.00</cantidad>
      <precioUnitario>13.38</precioUnitario>
      <descuento>0.00</descuento>
      <precioTotalSinImpuesto>13.38</precioTotalSinImpuesto>
      <impuestos>
        <impuesto>
          <codigo>2</codigo>
          <codigoPorcentaje>2</codigoPorcentaje>
          <tarifa>12</tarifa>
          <baseImponible>13.38</baseImponible>
          <valor>1.61</valor>
        </impuesto>
      </impuestos>
    </detalle>
  </detalles>
  <infoAdicional>
    <campoAdicional nombre="Direccion">asdcasdc sad casdc asdcasdc</campoAdicional>
    <campoAdicional nombre="Email">sdfvsdfvs@sdcdf.com</campoAdicional>
  </infoAdicional>
</factura>
*/
