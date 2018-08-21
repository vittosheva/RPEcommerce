<?php

namespace App\Http\Controllers\Backend\Billing;

use App\Models\Billing\Orders;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PrestashopOrdersController extends Controller
{
    protected $prestashopDbConnection;
    protected $orders;

    public function __construct()
    {
        $this->orders = new Orders();
    }

    public function index(Request $request)
    {
        // Get the pagination number or a default
        $items = $request->items ?? 10;

        $orders = $this->orders->getOrders($items);

        return view('backend.billing.index')
            ->with('orders', $orders)
            ->with('items', $items);
    }

    public function processOrder($id)
    {
        $invoice = new InvoiceController();
        $data = $invoice->process();

        return view('backend.billing.getdata')->with('responseData', $data);
    }

    public function verifyOrder($id)
    {
        dd('Verify Order: '. $id);
    }

    public function getOrderDetail($id)
    {
        if (env('DB_CONNECTION') == 'mysql_home') {
            $this->prestashopDbConnection = 'mysql_home2';
        } elseif (env('DB_CONNECTION') == 'mysql_work') {
            $this->prestashopDbConnection = 'mysql_work2';
        } else {
            $this->prestashopDbConnection = 'mysql_prod2';
        }

        $orderDetail = DB::connection($this->prestashopDbConnection)
            ->table('ps_order_detail')
            ->select('*')
            ->where('id_order', '=', $id)
            ->get();

        return '<div><div id="pip" style="position: relative; height: 100%;"><div style="background: #fff; padding: 30px; height: 100%;">'. $orderDetail->toJson(JSON_PRETTY_PRINT) .'</div></div></div>';
    }
}
