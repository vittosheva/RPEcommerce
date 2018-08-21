<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Billing\Orders;
use App\Http\Controllers\Backend\Billing\InvoiceController;

class ProcessOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:process';

    protected $orders;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process orders using RedPagos webservice';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->orders = new Orders();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $unprocessedOrders = $this->orders->unprocessedOrders();

        if ($unprocessedOrders) {
            $invoice = new InvoiceController();

            foreach ($unprocessedOrders as $key => $order) {
                $data = $invoice->process($order);
                dd($data);
            }
        }
    }
}
