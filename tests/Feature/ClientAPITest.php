<?php

namespace Tests\Feature;

use App\Enum\UserRoleEnum;
use App\Models\Client;
use App\Models\EwsDevice;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ClientAPITest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    public function test_client_api_call_index_expect_success()
    {
        $user = User::factory()
            ->hasAttached(Role::where('name', UserRoleEnum::DEV)->first())
            ->create();

        $this->actingAs($user);

        for ($i = 0; $i < 5; $i++) {
            EwsDevice::factory()->create();
        }

        for ($i = 0; $i < 5; $i++) {
            Client::factory()
                ->hasAttached(EwsDevice::inRandomOrder()->limit(mt_rand(1, 3))->get())
                ->withCodeIwant()->withClientUser()
                ->create();
        }

        $response = $this->json('GET', '/api/v1/clients');

        $response->assertStatus(200);
    }

    public function test_client_api_call_create_with_auto_code_expect_success()
    {
        $user = User::factory()
            ->hasAttached(Role::where('name', UserRoleEnum::DEV)->first())
            ->create();

        $this->actingAs($user);

        for ($i = 0; $i < 5; $i++) {
            EwsDevice::factory()->create();
        }

        $client = Client::factory()
            ->withCredentials()
            ->make(['code' => 'AUTO'])
            ->toArray();

        $client['ews_devices'] = EwsDevice::inRandomOrder()->limit(mt_rand(1, 3))->get()->pluck('id')->toArray();

        $response = $this->json('POST', '/api/v1/client', $client);

        $response->assertSuccessful();

        $client['code'] = $response['data']['code'];

        $client = Arr::except($client, ['email', 'password', 'ews_devices']);

        $this->assertDatabaseHas('clients', $client);
    }

    public function test_client_api_call_show_expect_success()
    {
        $user = User::factory()
            ->hasAttached(Role::where('name', UserRoleEnum::DEV)->first())
            ->create();

        $this->actingAs($user);

        for ($i = 0; $i < 5; $i++) {
            EwsDevice::factory()->create();
        }

        $client = Client::factory()
            ->hasAttached(EwsDevice::inRandomOrder()->limit(mt_rand(1, 3))->get())
            ->withCodeIwant()->withClientUser()
            ->create();

        $response = $this->json('GET', '/api/v1/client/'.$client->id);

        $response->assertSuccessful();
    }

    public function test_client_api_call_update_with_auto_code_expect_success()
    {
        $user = User::factory()
            ->hasAttached(Role::where('name', UserRoleEnum::DEV)->first())
            ->create();

        $this->actingAs($user);

        for ($i = 0; $i < 5; $i++) {
            EwsDevice::factory()->create();
        }

        $client = Client::factory()
            ->hasAttached(EwsDevice::inRandomOrder()->limit(mt_rand(1, 3))->get())
            ->withCodeIwant()->withClientUser()
            ->create();

        $updatedClient = Client::factory()
            ->make(['code' => 'AUTO'])
            ->toArray();
        $updatedClient['ews_devices'] = EwsDevice::inRandomOrder()->limit(mt_rand(1, 3))->get()->pluck('id')->toArray();

        $response = $this->json('POST', '/api/v1/client/'.$client->id, $updatedClient);

        $response->assertSuccessful();

        $updatedClient['code'] = $response['data']['code'];

        $updatedClient = Arr::except($updatedClient, ['ews_devices']);

        $this->assertDatabaseHas('clients', $updatedClient);
    }

    public function test_client_api_call_update_with_existing_code_expect_success()
    {
        $user = User::factory()
            ->hasAttached(Role::where('name', UserRoleEnum::DEV)->first())
            ->create();

        $this->actingAs($user);

        for ($i = 0; $i < 5; $i++) {
            EwsDevice::factory()->create();
        }

        $client = Client::factory()
            ->hasAttached(EwsDevice::inRandomOrder()->limit(mt_rand(1, 3))->get())
            ->withCodeIwant()->withClientUser()
            ->create();

        $updatedClient = Client::factory()
            ->withCodeIwant()
            ->make(['code' => $client->code])
            ->toArray();
        $updatedClient['ews_devices'] = EwsDevice::inRandomOrder()->limit(mt_rand(1, 3))->get()->pluck('id')->toArray();

        $response = $this->json('POST', '/api/v1/client/'.$client->id, $updatedClient);

        $response->assertSuccessful();

        $updatedClient['code'] = $response['data']['code'];

        $updatedClient = Arr::except($updatedClient, ['ews_devices']);

        $this->assertDatabaseHas('clients', $updatedClient);
    }

    public function test_client_api_call_update_with_existing_code_in_different_client_expect_failed()
    {
        $user = User::factory()
            ->hasAttached(Role::where('name', UserRoleEnum::DEV)->first())
            ->create();

        $this->actingAs($user);

        for ($i = 0; $i < 5; $i++) {
            EwsDevice::factory()->create();
        }

        $existingClient = Client::factory()
            ->hasAttached(EwsDevice::inRandomOrder()->limit(mt_rand(1, 3))->get())
            ->withCodeIwant()->withClientUser()
            ->create();

        $newClient = Client::factory()
            ->hasAttached(EwsDevice::inRandomOrder()->limit(mt_rand(1, 3))->get())
            ->withCodeIwant()->withClientUser()
            ->create();

        $updatedClient = Client::factory()
            ->make(['code' => $newClient->code])
            ->toArray();

        $updatedClient['ews_devices'] = EwsDevice::inRandomOrder()->limit(mt_rand(1, 3))->get()->pluck('id')->toArray();

        $response = $this->json('POST', '/api/v1/client/'.$existingClient->id, $updatedClient);

        $response->assertStatus(422);
    }

    public function test_client_api_call_delete_expect_success()
    {
        $user = User::factory()
            ->hasAttached(Role::where('name', UserRoleEnum::DEV)->first())
            ->create();

        $this->actingAs($user);

        $client = Client::factory()
            ->withCodeIwant()->withClientUser()
            ->create();

        $response = $this->json('DELETE', '/api/v1/client/'.$client->id);

        $response->assertSuccessful();

        $this->assertSoftDeleted('clients', ['id' => $client->id]);
    }
}
