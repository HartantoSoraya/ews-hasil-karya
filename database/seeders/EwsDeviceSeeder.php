<?php

namespace Database\Seeders;

use App\Models\EwsDevice;
use App\Models\EwsDeviceAddressHistory;
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
            $ewsDevice = EwsDevice::factory()->create();

            $ewsDeviceAddressHistory = new EwsDeviceAddressHistory();
            $ewsDeviceAddressHistory->ews_device_id = $ewsDevice->id;
            $ewsDeviceAddressHistory->province = $ewsDevice->province;
            $ewsDeviceAddressHistory->regency = $ewsDevice->regency;
            $ewsDeviceAddressHistory->district = $ewsDevice->district;
            $ewsDeviceAddressHistory->subdistrict = $ewsDevice->subdistrict;
            $ewsDeviceAddressHistory->address = $ewsDevice->address;
            $ewsDeviceAddressHistory->save();
        }
    }
}
