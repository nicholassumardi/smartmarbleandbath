<?php

namespace App\Console\Commands;

use App\Models\Unit;
use Illuminate\Console\Command;

class NewUnits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'new:units';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checking status order';

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
        $data = Unit::create([
			'code' 		=> uniqid(),
			'name'		=> 'KOMPAWE',
			'status' 	=> '1'
		]);

        return true;
    }
}
