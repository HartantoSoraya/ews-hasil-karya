<?php

namespace Tests\Feature;

use App\Models\EwsDevice;
use App\Models\EwsDeviceAddress;
use App\Models\EwsDeviceMeasurement;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EwsDeviceMeasurementAPITest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    public function test_ews_device_measurement_call_index_expect_success()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        EwsDevice::factory()
            ->has(EwsDeviceAddress::factory()->count(mt_rand(1, 3)), 'addresses')
            ->count(5)->create();

        EwsDeviceMeasurement::factory()
            ->for(EwsDevice::inRandomOrder()->first(), 'device')
            ->count(5)->create();

        $response = $this->json('GET', '/api/v1/ews-device-measurements');

        $response->assertSuccessful();
    }

    public function test_ews_device_measurement_call_create_expect_success()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        EwsDevice::factory()
            ->has(EwsDeviceAddress::factory()->count(mt_rand(1, 3)), 'addresses')
            ->create();

        $ewsDeviceMeasurement = EwsDeviceMeasurement::factory()
            ->make(['device_code' => EwsDevice::inRandomOrder()->first()->code])
            ->toArray();

        $response = $this->json('POST', '/api/v1/ews-device-measurement', $ewsDeviceMeasurement);

        $response->assertSuccessful();

        unset($ewsDeviceMeasurement['device_code']);

        $this->assertDatabaseHas('ews_device_measurements', $ewsDeviceMeasurement);
    }

    public function test_ews_device_measurement_api_call_show_expect_success()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $ewsDevice = EwsDevice::factory()
            ->has(EwsDeviceAddress::factory()->count(mt_rand(1, 3)), 'addresses')
            ->create();

        $ewsDeviceMeasurement = EwsDeviceMeasurement::factory()
            ->for($ewsDevice, 'device')
            ->create();

        $response = $this->json('GET', '/api/v1/ews-device-measurement/'.$ewsDeviceMeasurement->id);

        $response->assertSuccessful();
    }

    public function test_ews_device_measurement_api_call_update_expect_success()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $ewsDevice = EwsDevice::factory()
            ->has(EwsDeviceAddress::factory()->count(mt_rand(1, 3)), 'addresses')
            ->create();

        $ewsDeviceMeasurement = EwsDeviceMeasurement::factory()
            ->for($ewsDevice, 'device')
            ->create();

        $updatedEwsDeviceMeasurement = EwsDeviceMeasurement::factory()
            ->make(['device_code' => EwsDevice::inRandomOrder()->first()->code])
            ->toArray();

        $response = $this->json('POST', '/api/v1/ews-device-measurement/'.$ewsDeviceMeasurement->id, $updatedEwsDeviceMeasurement);

        $response->assertSuccessful();

        $updatedEwsDeviceMeasurement['ews_device_id'] = $response['data']['ews_device']['id'];
        unset($updatedEwsDeviceMeasurement['device_code']);

        $this->assertDatabaseHas('ews_device_measurements', $updatedEwsDeviceMeasurement);
    }

    public function test_ews_device_measurement_api_call_delete_expect_success()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $ewsDevice = EwsDevice::factory()
            ->has(EwsDeviceAddress::factory()->count(mt_rand(1, 3)), 'addresses')
            ->create();

        $ewsDeviceMeasurement = EwsDeviceMeasurement::factory()
            ->for($ewsDevice, 'device')
            ->create();

        $response = $this->json('DELETE', '/api/v1/ews-device-measurement/'.$ewsDeviceMeasurement->id);

        $response->assertSuccessful();

        unset($ewsDeviceMeasurement['device_code']);

        $this->assertSoftDeleted('ews_device_measurements', $ewsDeviceMeasurement->toArray());
    }
}
