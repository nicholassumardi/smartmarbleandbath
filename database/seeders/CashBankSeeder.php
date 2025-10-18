<?php

namespace Database\Seeders;

use App\Models\CashBank;
use App\Models\CashBankDetail;
use Illuminate\Database\Seeder;

class CashBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($cash_banks as $cb) {
            CashBank::insert([
                'id'          => $cb['id'],
                'user_id'     => $cb['user_id'],
                'code'        => $cb['code'],
                'date'        => $cb['date'],
                'type'        => $cb['type'],
                'description' => $cb['description'],
                'created_at'  => $cb['created_at'],
                'updated_at'  => $cb['updated_at'],
                'deleted_at'  => $cb['deleted_at']
            ]);
        }

        foreach($cash_bank_details as $cbd) {
            CashBankDetail::insert([
                'id'           => $cbd['id'],
                'cash_bank_id' => $cbd['cash_bank_id'],
                'coa_id'       => $cbd['coa_id'],
                'type'         => $cbd['type'],
                'nominal'      => $cbd['nominal'],
                'note'         => $cbd['note'],
                'created_at'   => $cbd['created_at'],
                'updated_at'   => $cbd['updated_at']
            ]);
        }
    }
}
