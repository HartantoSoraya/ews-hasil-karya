<?php

namespace Tests\Feature;

use App\Enum\UserRoleEnum;
use App\Models\EwsDevice;
use App\Models\EwsDeviceAddressHistory;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class EwsDeviceAPITest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    public function test_ews_device_call_index_expect_success()
    {
        $user = User::factory()
            ->hasAttached(Role::where('name', '=', UserRoleEnum::DEV)->first())
            ->create();

        $this->actingAs($user);

        for ($i = 0; $i < 5; $i++) {
            $ewsDevice = EwsDevice::factory()->withExpectedCode()->create();

            EwsDeviceAddressHistory::factory()->create([
                'ews_device_id' => $ewsDevice->id,
                'province' => $ewsDevice->province,
                'regency' => $ewsDevice->regency,
                'district' => $ewsDevice->district,
                'subdistrict' => $ewsDevice->subdistrict,
                'address' => $ewsDevice->address,
            ]);
        }

        $response = $this->json('GET', '/api/v1/ews-devices');

        $response->assertSuccessful();
    }

    public function test_ews_device_call_create_with_auto_code_expect_success()
    {
        $user = User::factory()
            ->hasAttached(Role::where('name', '=', UserRoleEnum::DEV)->first())
            ->create();

        $this->actingAs($user);

        $ewsDevice = EwsDevice::factory()->make(['code' => 'AUTO'])->toArray();

        $response = $this->json('POST', '/api/v1/ews-device', $ewsDevice);

        $response->assertSuccessful();

        $ewsDevice['code'] = $response['data']['code'];

        $this->assertDatabaseHas('ews_devices', $ewsDevice);
    }

    public function test_ews_device_api_call_show_expect_success()
    {
        $user = User::factory()
            ->hasAttached(Role::where('name', '=', UserRoleEnum::DEV)->first())
            ->create();

        $this->actingAs($user);

        $ewsDevice = EwsDevice::factory()->create();

        EwsDeviceAddressHistory::factory()->create([
            'ews_device_id' => $ewsDevice->id,
            'province' => $ewsDevice->province,
            'regency' => $ewsDevice->regency,
            'district' => $ewsDevice->district,
            'subdistrict' => $ewsDevice->subdistrict,
            'address' => $ewsDevice->address,
        ]);

        $response = $this->json('GET', '/api/v1/ews-device/'.$ewsDevice->id);

        $response->assertSuccessful();
    }

    public function test_ews_device_api_call_update_with_auto_code_expect_success()
    {
        $user = User::factory()
            ->hasAttached(Role::where('name', '=', UserRoleEnum::DEV)->first())
            ->create();

        $this->actingAs($user);

        $ewsDevice = EwsDevice::factory()->create();

        EwsDeviceAddressHistory::factory()->create([
            'ews_device_id' => $ewsDevice->id,
            'province' => $ewsDevice->province,
            'regency' => $ewsDevice->regency,
            'district' => $ewsDevice->district,
            'subdistrict' => $ewsDevice->subdistrict,
            'address' => $ewsDevice->address,
        ]);

        $updatedEwsDevice = EwsDevice::factory()->make(['code' => 'AUTO'])->toArray();

        $response = $this->json('POST', '/api/v1/ews-device/'.$ewsDevice->id, $updatedEwsDevice);

        $response->assertSuccessful();

        $updatedEwsDevice['code'] = $response['data']['code'];

        $response->assertJsonFragment($updatedEwsDevice);
    }

    public function test_ews_device_api_call_update_with_existing_code_expect_success()
    {
        $user = User::factory()
            ->hasAttached(Role::where('name', '=', UserRoleEnum::DEV)->first())
            ->create();

        $this->actingAs($user);

        $ewsDevice = EwsDevice::factory()->create();

        EwsDeviceAddressHistory::factory()->create([
            'ews_device_id' => $ewsDevice->id,
            'province' => $ewsDevice->province,
            'regency' => $ewsDevice->regency,
            'district' => $ewsDevice->district,
            'subdistrict' => $ewsDevice->subdistrict,
            'address' => $ewsDevice->address,
        ]);

        $updatedEwsDevice = EwsDevice::factory()->make(['code' => $ewsDevice->code])->toArray();

        $response = $this->json('POST', '/api/v1/ews-device/'.$ewsDevice->id, $updatedEwsDevice);

        $response->assertSuccessful();

        $this->assertDatabaseHas('ews_devices', $updatedEwsDevice);
    }

    public function test_ews_device_api_update_with_existing_code_in_different_ews_device_expect_failed()
    {
        $user = User::factory()
            ->hasAttached(Role::where('name', '=', UserRoleEnum::DEV)->first())
            ->create();

        $this->actingAs($user);

        $existingEwsDevice = EwsDevice::factory()->create();

        EwsDeviceAddressHistory::factory()->create([
            'ews_device_id' => $existingEwsDevice->id,
            'province' => $existingEwsDevice->province,
            'regency' => $existingEwsDevice->regency,
            'district' => $existingEwsDevice->district,
            'subdistrict' => $existingEwsDevice->subdistrict,
            'address' => $existingEwsDevice->address,
        ]);

        $newEwsDevice = EwsDevice::factory()->create();

        EwsDeviceAddressHistory::factory()->create([
            'ews_device_id' => $newEwsDevice->id,
            'province' => $newEwsDevice->province,
            'regency' => $newEwsDevice->regency,
            'district' => $newEwsDevice->district,
            'subdistrict' => $newEwsDevice->subdistrict,
            'address' => $newEwsDevice->address,
        ]);

        $updatedEwsDevice = EwsDevice::factory()->make(['code' => $existingEwsDevice->code])->toArray();

        $response = $this->json('POST', '/api/v1/ews-device/'.$newEwsDevice->id, $updatedEwsDevice);

        $response->assertStatus(422);
    }

    public function test_ews_device_api_call_delete_expect_success()
    {
        $user = User::factory()
            ->hasAttached(Role::where('name', '=', UserRoleEnum::DEV)->first())
            ->create();

        $this->actingAs($user);

        $ewsDevice = EwsDevice::factory()->create();

        EwsDeviceAddressHistory::factory()->create([
            'ews_device_id' => $ewsDevice->id,
            'province' => $ewsDevice->province,
            'regency' => $ewsDevice->regency,
            'district' => $ewsDevice->district,
            'subdistrict' => $ewsDevice->subdistrict,
            'address' => $ewsDevice->address,
        ]);

        $response = $this->json('DELETE', '/api/v1/ews-device/'.$ewsDevice->id);

        $response->assertSuccessful();

        $ewsDevice = $ewsDevice->toArray();
        $ewsDevice = Arr::except($ewsDevice, ['created_at', 'updated_at', 'deleted_at']);

        $this->assertSoftDeleted('ews_devices', $ewsDevice);
    }
}
