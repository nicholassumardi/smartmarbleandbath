<?php

namespace App\Console\Commands;

use App\Models\Stock;
use App\Models\ProductShading;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class StockCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize Stock In Ventura';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $data = ProductShading::chunk(100, function($query) {
                foreach($query as $q) {
                    $stock = json_decode(Http::retry(3, 100)->post(env('VENTURA') . 'ventura/item/stock', [
                        'kode_item' => $q->stock_code,
                        'gudang'    => $q->warehouse_code,
                        'per_page'  => 1000
                    ]));

                    if($stock->result->total_data > 0) {
                        foreach($stock->result->data as $s) {
                            $q->update(['qty' => $s->stok]);
                        }
                    } else {
                        /* $q->delete(); */
						$q->update(['qty' => 0]);
                    }
                }
            });

        return true;
    }
}
