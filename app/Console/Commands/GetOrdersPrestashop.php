<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Billing\Orders;
use Illuminate\Support\Carbon;

class GetOrdersPrestashop extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Prestashop orders table for new entries';

    protected $prestashopDbConnection;
    protected $orders;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        if (env('DB_CONNECTION') == 'mysql_home') {
            $this->prestashopDbConnection = 'mysql_home2';
        } elseif (env('DB_CONNECTION') == 'mysql_work') {
            $this->prestashopDbConnection = 'mysql_work2';
        } else {
            $this->prestashopDbConnection = 'mysql_prod2';
        }

        $this->orders = new Orders();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Get last order inserted (PRESTASHOP)
        $prestashopOrder = DB::connection($this->prestashopDbConnection)
            ->table('ps_orders')
            ->select('id_order')
            ->orderBy('id_order', 'DESC')
            ->first();

        // Get last order inserted (LARAVEL)
        $laravelOrder = $this->orders->getLastOrder();

        if ($laravelOrder === null || $laravelOrder->id_order == 0 || $laravelOrder->id_order == '' || $laravelOrder->id_order === null || $prestashopOrder->id_order > $laravelOrder->id_order)
        {
            // Prestashop's order table
            $orders = DB::connection($this->prestashopDbConnection)
                ->table('ps_orders')
                ->leftJoin('ps_shop', 'ps_orders.id_shop', '=', 'ps_shop.id_shop')
                ->leftJoin('ps_shop_group', 'ps_orders.id_shop_group', '=', 'ps_shop_group.id_shop_group')
                ->leftJoin('ps_carrier', 'ps_orders.id_carrier', '=', 'ps_carrier.id_carrier')
                ->leftJoin('ps_customer', 'ps_orders.id_customer', '=', 'ps_customer.id_customer')
                ->leftJoin('ps_address', 'ps_orders.id_address_invoice', '=', 'ps_address.id_address')
                ->leftJoin('ps_currency', 'ps_orders.id_currency', '=', 'ps_currency.id_currency')
                ->leftJoin('ps_order_state', 'ps_orders.current_state', '=', 'ps_order_state.id_order_state')
                ->leftJoin('ps_order_state_lang', 'ps_orders.current_state', '=', 'ps_order_state_lang.id_order_state')
                ->leftJoin('ps_order_invoice', 'ps_orders.id_order', '=', 'ps_order_invoice.id_order')
                ->select(
                    // ps_orders
                    'ps_orders.id_order',
                    'ps_orders.reference',
                    'ps_orders.payment',
                    'ps_orders.module',
                    'ps_orders.shipping_number',
                    'ps_orders.total_discounts',
                    'ps_orders.total_discounts_tax_incl',
                    'ps_orders.total_discounts_tax_excl',
                    'ps_orders.total_paid',
                    'ps_orders.total_paid_tax_incl',
                    'ps_orders.total_paid_tax_excl',
                    'ps_orders.total_paid_real',
                    'ps_orders.total_products',
                    'ps_orders.total_products_wt',
                    'ps_orders.total_shipping',
                    'ps_orders.total_shipping_tax_incl',
                    'ps_orders.total_shipping_tax_excl',
                    'ps_orders.carrier_tax_rate',
                    'ps_orders.total_wrapping',
                    'ps_orders.total_wrapping_tax_incl',
                    'ps_orders.total_wrapping_tax_excl',
                    'ps_orders.invoice_number',
                    'ps_orders.invoice_date',
                    'ps_orders.date_add',

                    // ps_shop
                    'ps_shop.name as shop',

                    // ps_shop_group
                    'ps_shop_group.name as shop_group',

                    // ps_carrier
                    'ps_carrier.name as carrier',

                    // ps_customer
                    'ps_customer.email as customer_email',
                    'ps_customer.firstname as customer_firstname',
                    'ps_customer.lastname as customer_lastname',
                    DB::raw('concat(ps_customer.firstname," ",ps_customer.lastname) as customer_fullname'),
                    'ps_customer.website as customer_website',

                    // ps_address
                    'ps_address.address1 as customer_address1',
                    'ps_address.address2 as customer_address2',
                    'ps_address.postcode as customer_postcode',
                    'ps_address.city as customer_city',
                    'ps_address.phone as customer_phone',
                    'ps_address.phone_mobile as customer_phone_mobile',
                    'ps_address.dni as customer_dni',

                    // ps_currency
                    'ps_currency.name as name',

                    // ps_order_state
                    'ps_order_state.color as status_bg_color',

                    // ps_order_state_lang
                    'ps_order_state_lang.name as status_name',

                    // ps_order_invoice
                    'ps_order_invoice.id_order_invoice as id_order_invoice'
                );

            // New or continue with id
            if ($laravelOrder === null || $laravelOrder->id_order == 0 || $laravelOrder->id_order == '' || $laravelOrder->id_order === null) {
                $orders = $orders->where('ps_orders.id_order', '>=', 1);
            } else {
                $orders = $orders->where('ps_orders.id_order', '>', $laravelOrder->id_order);
            }

            $orders = $orders->orderBy('ps_orders.id_order', 'ASC');
            $orders = $orders->get();

            // Check content
            if (count($orders) > 0) {
                foreach ($orders as $order) {
                    $now = Carbon::now('America/Guayaquil')->format('Y-m-d H:i:s');
                    $orderItem = [
                        'id_order'              => $order->id_order,
                        'invoice_number'        => $order->invoice_number,
                        'razon_social'          => $order->shop,
                        'nombre_comercial'      => $order->shop,
                        'ced_ruc'               => $order->customer_dni,
                        'firstname'             => $order->customer_firstname,
                        'lastname'              => $order->customer_lastname,
                        'fullname'              => $order->customer_fullname,
                        'email'                 => $order->customer_email,
                        'address1'              => $order->customer_address1,
                        'address2'              => $order->customer_address2,
                        'postcode'              => $order->customer_postcode,
                        'city'                  => $order->customer_city,
                        'phone'                 => $order->customer_phone,
                        'phone_mobile'          => $order->customer_phone_mobile,
                        'total_paid_tax_incl'   => $order->total_paid_tax_incl,
                        'total_paid_tax_excl'   => $order->total_paid_tax_excl,
                        'total_discounts'       => $order->total_discounts,
                        'tax'                   => $order->total_paid_tax_incl - $order->total_paid_tax_excl,
                        'invoice_date'          => ($order->invoice_date == '0000-00-00 00:00:00') ? $order->date_add : $order->invoice_date,
                        'created_at'            => $now,
                        'updated_at'            => $now,
                        'status'                => 0
                    ];

                    // Insert new order
                    DB::table('orders')->insert($orderItem);

                    // Get Prestashop's order details table with new inserted id
                    $orderDetails = DB::connection($this->prestashopDbConnection)
                        ->table('ps_orders')
                        ->join('ps_order_detail', 'ps_orders.id_order', '=', 'ps_order_detail.id_order')
                        ->select('ps_order_detail.*')
                        ->where('ps_orders.id_order', '=', $orderItem['id_order'])
                        ->orderBy('ps_orders.id_order', 'ASC')
                        ->get();

                    // Check content
                    if (count($orderDetails) > 0) {
                        $detailItems = [];

                        foreach ($orderDetails as $detail) {
                            $detailItems []= [
                                'id_order_detail'               => $detail->id_order_detail,
                                'id_order'                      => $detail->id_order,
                                'id_order_invoice'              => $detail->id_order_invoice,
                                'id_shop'                       => $detail->id_shop,
                                'product_id'                    => $detail->product_id,
                                'product_attribute_id'          => $detail->product_attribute_id,
                                'product_name'                  => $detail->product_name,
                                'product_quantity'              => $detail->product_quantity,
                                'product_price'                 => $detail->product_price,
                                'product_quantity_discount'     => $detail->product_quantity_discount,
                                'product_reference'             => $detail->product_reference,
                                'unit_price_tax_excl'           => $detail->unit_price_tax_excl,
                                'total_price_tax_excl'          => $detail->total_price_tax_excl,
                                'tax'                           => $detail->total_price_tax_excl - $detail->total_price_tax_incl
                            ];
                        }

                        // Insert new order details
                        if (count($detailItems) > 0) {
                            DB::table('order_detail')->insert($detailItems);
                        }
                    }
                }
            }
        }
    }
}
