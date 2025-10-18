<?php

namespace App\Console\Commands;

use App\Models\Warehouse;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class WarehouseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'warehouse:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize Warehouse In Ventura';

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
        $get_page   = json_decode(Http::post(env('VENTURA') . 'ventura/warehouse'));
        $total_page = $get_page->result->total_page;

        for($i = 1; $i <= $total_page; $i++) {
            $warehouse = json_decode(Http::post(env('VENTURA') . 'ventura/warehouse', [
                'page' => $i
            ]));

            foreach($warehouse->result->data as $w) {
                Warehouse::updateOrCreate([
                    'code'   => $w->kode,
                    'name'   => $w->nama,
					'type'	 => '1',
                ], [
                    'status' => $w->isaktif == 1 ? 1 : 2
                ]);
            }
        }

        return true;
    }
}
