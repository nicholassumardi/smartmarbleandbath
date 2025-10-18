<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(WarehouseSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(CurrencySeeder::class);
        $this->call(BrandSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(SurfaceSeeder::class);
        $this->call(ColorSeeder::class);
        $this->call(PatternSeeder::class);
        $this->call(GradeSeeder::class);
        $this->call(HsCodeSeeder::class);
        $this->call(UnitSeeder::class);
        $this->call(LoadingLimitSeeder::class);
        $this->call(CompanySeeder::class);
        $this->call(DivisionSeeder::class);
        $this->call(SupplierSeeder::class);
        $this->call(BannerSeeder::class);
        $this->call(TypeSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(CurrencyRateSeeder::class);
        $this->call(AgentSeeder::class);
        $this->call(ImportSeeder::class);
        $this->call(CitySeeder::class);
        $this->call(FreightSeeder::class);
        $this->call(EmklSeeder::class);
        $this->call(MarketingStructureSeeder::class);
        $this->call(CustomerSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CoaSeeder::class);
        $this->call(JobDescSeeder::class);
        $this->call(BudgetingSeeder::class);
        $this->call(CashBankSeeder::class);
        $this->call(JournalSeeder::class);
        $this->call(VendorSeeder::class);
        $this->call(TransportSeeder::class);
        $this->call(DeliverySeeder::class);
        $this->call(NewsSeeder::class);
    }
}
