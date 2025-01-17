<?php

namespace Tests\Feature;

use App\Enum\UserRoleEnum;
use App\Models\EwsDevice;
use App\Models\EwsDeviceMeasurement;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
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
        $user = User::factory()
            ->hasAttached(Role::where('name', '=', UserRoleEnum::DEV)->first())
            ->create();

        $this->actingAs($user);

        for ($i = 0; $i < 5; $i++) {
            EwsDevice::factory()->withExpectedCode()->create();
        }

        $ewsDevice = EwsDevice::inRandomOrder()->first();
        $startDate = now()->startOfMonth()->toDateString();
        $endDate = now()->endOfMonth()->toDateString();

        EwsDeviceMeasurement::factory()
            ->for($ewsDevice, 'device')
            ->count(100)->create(
                ['created_at' => now()->startOfMonth()->addDays(rand(1, 15))->toDateTimeString()]
            );

        $response = $this->json('GET', '/api/v1/ews-device-measurements', [
            'ews_device_id' => $ewsDevice->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        $response->assertSuccessful();
    }

    public function test_ews_device_measurement_call_create_expect_success()
    {
        $user = User::factory()
            ->hasAttached(Role::where('name', '=', UserRoleEnum::DEV)->first())
            ->create();

        $this->actingAs($user);

        EwsDevice::factory()->create();

        $ewsDeviceMeasurement = EwsDeviceMeasurement::factory()
            ->make(['device_code' => EwsDevice::inRandomOrder()->first()->code])
            ->toArray();

        $response = $this->json('POST', '/api/v1/ews-device-measurement', $ewsDeviceMeasurement);

        $response->assertSuccessful();

        unset($ewsDeviceMeasurement['device_code']);

        $this->assertDatabaseHas('ews_device_measurements', $ewsDeviceMeasurement);
    }

    public function test_ews_device_measurement_call_create_with_api_token_expect_success()
    {
        $this->markTestSkipped('This test is skipped because it requires an API token.');

        EwsDevice::factory()->create();

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
        $user = User::factory()
            ->hasAttached(Role::where('name', '=', UserRoleEnum::DEV)->first())
            ->create();

        $this->actingAs($user);

        $ewsDevice = EwsDevice::factory()->create();

        $ewsDeviceMeasurement = EwsDeviceMeasurement::factory()
            ->for($ewsDevice, 'device')
            ->create();

        $response = $this->json('GET', '/api/v1/ews-device-measurement/'.$ewsDeviceMeasurement->id);

        $response->assertSuccessful();
    }

    public function test_ews_device_measurement_api_call_update_expect_success()
    {
        $user = User::factory()
            ->hasAttached(Role::where('name', '=', UserRoleEnum::DEV)->first())
            ->create();

        $this->actingAs($user);

        $ewsDevice = EwsDevice::factory()->create();

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
        $user = User::factory()
            ->hasAttached(Role::where('name', '=', UserRoleEnum::DEV)->first())
            ->create();

        $this->actingAs($user);

        $ewsDevice = EwsDevice::factory()->create();

        $ewsDeviceMeasurement = EwsDeviceMeasurement::factory()
            ->for($ewsDevice, 'device')
            ->create();

        $response = $this->json('DELETE', '/api/v1/ews-device-measurement/'.$ewsDeviceMeasurement->id);

        $response->assertSuccessful();

        $ewsDeviceMeasurement = $ewsDeviceMeasurement->toArray();
        $ewsDeviceMeasurement = Arr::except($ewsDeviceMeasurement, ['device_code', 'created_at', 'updated_at', 'deleted_at']);

        $this->assertSoftDeleted('ews_device_measurements', $ewsDeviceMeasurement);
    }
}
