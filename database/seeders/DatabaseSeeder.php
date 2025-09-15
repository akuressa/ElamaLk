<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {        
        $this->call(UsersSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(CurrencyTableSeeder::class);        
        $this->call(CountriesTableSeeder::class);
        $this->call(StatesSeeder::class);
        $this->call(CitiesSeeder::class);
        $this->call(PlansSeeder::class);
        $this->call(PaymentGatewaySeeder::class);
        $this->call(PagesSeeder::class);
    }
}
