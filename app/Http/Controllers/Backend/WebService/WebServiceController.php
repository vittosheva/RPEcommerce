<?php

namespace App\Http\Controllers\Backend\WebService;

use App\Http\Controllers\Controller;
use SoapClient;

class WebServiceController extends Controller
{
    protected $soapClient     = '';
    protected $wsdl_url       = 'http://redfactura.ec:8282/Pruebas/KEB_Empresas/operations.asmx?WSDL';
    protected $user           = 'PortalRedpagos';
    protected $pass           = 'R3dPaG0s20183C0mm3rc3';
    protected $userEntityId   = '0E880886-BEF6-411D-A38E-3FA99B2E40CF';

    public function __construct()
    {
        $option             = ['trace' => 1];
        $url                = $this->wsdl_url;
        $this->soapClient   = new SoapClient($url, $option);
    }

    /**
     * Add Files With App to RedPagos Web Service (SOAP)
     *
     * @param null $xml
     * @param null $clientContact
     * @return mixed
     */
    public function AddFilesWithApp($xml = null, $clientContact = null)
    {
        $params = [
            'docAppRefCode'     => '1',
            'xmlString'         => $xml,
            'docTypeCode'       => '01',
            'docNum'            => '1',
            'referencia'        => env('APP_URL'),
            'docRef'            => 'hola mundo',
            'sendNotification'  => 1,
            'clientContact'     => $clientContact,
            'user'              => $this->user,
            'pass'              => $this->pass,
            'userEntityId'      => $this->userEntityId
        ];

        return $this->soapClient->AddFilesWithApp($params);
    }

    /**
     * Get Data from RedPagos Web Service (SOAP)
     *
     * @param $DocumentId
     * @return mixed
     */
    public function GetData($DocumentId)
    {
        $params = [
            'documentId'    => $DocumentId, 
            'user'          => $this->user,
            'pass'          => $this->pass,
            'userEntityId'  => $this->userEntityId
        ];

        return $this->soapClient->GetData($params);
    }
}
