<?php

namespace Database\Seeders;

use App\Models\EwsDevice;
use App\Models\EwsDeviceAddress;
use Illuminate\Database\Seeder;

class EwsDeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $count = 3;
        for ($i = 1; $i <= $count; $i++) {
            EwsDevice::factory()
                ->has(EwsDeviceAddress::factory(), 'addresses')
                ->create();
        }
    }
}
