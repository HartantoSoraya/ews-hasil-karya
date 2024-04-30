<?php

namespace Tests\Feature;

use App\Models\NotificationRecepient;
use App\Models\User;
use Illuminate\Support\Arr;
use Tests\TestCase;

class NotificationRecepientAPITest extends TestCase
{
    public function test_notification_recepient_api_call_index_expect_success()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        for ($i = 0; $i < 10; $i++) {
            NotificationRecepient::factory()->create();
        }

        $response = $this->json('GET', 'api/v1/notification-recepients');

        $response->assertSuccessful();
    }

    public function test_notification_recepient_api_call_create_expect_success()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $notificationRecepient = NotificationRecepient::factory()->make()->toArray();

        $api = $this->json('POST', 'api/v1/notification-recepient', $notificationRecepient);

        $api->assertSuccessful();

        $this->assertDatabaseHas('notification_recepients', $notificationRecepient);
    }

    public function test_notification_recepient_api_call_create_without_required_data_expect_failed()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        // name
        $notificationRecepient = NotificationRecepient::factory()->make(['name' => null])->toArray();

        $api = $this->json('POST', 'api/v1/blog-category', $notificationRecepient);

        $api->assertStatus(422);

        $notificationRecepient = NotificationRecepient::factory()->make()->toArray();
        unset($notificationRecepient['name']);

        $api->assertStatus(422);

        // phone number
        $notificationRecepient = NotificationRecepient::factory()->make(['phone_number' => null])->toArray();

        $api = $this->json('POST', 'api/v1/blog-category', $notificationRecepient);

        $api->assertStatus(422);

        $notificationRecepient = NotificationRecepient::factory()->make()->toArray();
        unset($notificationRecepient['phone_number']);

        $api->assertStatus(422);

        // job title
        $notificationRecepient = NotificationRecepient::factory()->make(['job_title' => null])->toArray();

        $api = $this->json('POST', 'api/v1/blog-category', $notificationRecepient);

        $api->assertStatus(422);

        $notificationRecepient = NotificationRecepient::factory()->make()->toArray();
        unset($notificationRecepient['job_title']);

        $api->assertStatus(422);

        // is active
        $notificationRecepient = NotificationRecepient::factory()->make(['is_active' => null])->toArray();

        $api = $this->json('POST', 'api/v1/blog-category', $notificationRecepient);

        $api->assertStatus(422);

        $notificationRecepient = NotificationRecepient::factory()->make()->toArray();
        unset($notificationRecepient['is_active']);

        $api->assertStatus(422);
    }

    
    public function test_notification_recepient_api_call_create_with_empty_array_expect_failed()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $notificationRecepient = [];

        $api = $this->json('POST', 'api/v1/notification-recepient', $notificationRecepient);

        $api->assertStatus(422);
    }
    
    public function test_notification_recepient_api_call_show_expect_success()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $notificationRecepient = NotificationRecepient::factory()->create();

        $response = $this->json('GET', 'api/v1/notification-recepient/'.$notificationRecepient->id);

        $response->assertSuccessful();
    }
    
    public function test_notification_recepient_api_call_update_expect_success()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $notificationRecepient = NotificationRecepient::factory()->create();

        $newNotificationRecepient = NotificationRecepient::factory()->make()->toArray();

        $api = $this->json('POST', 'api/v1/notification-recepient/'.$notificationRecepient->id, $newNotificationRecepient);

        $api->assertSuccessful();

        $this->assertDatabaseHas('notification_recepients', $newNotificationRecepient);
    }
    
    public function test_notification_recepient_api_call_delete_expect_success()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $notificationRecepient = NotificationRecepient::factory()->create();

        $api = $this->json('DELETE', 'api/v1/notification-recepient/'.$notificationRecepient->id);

        $api->assertSuccessful();

        $this->assertSoftDeleted('notification_recepients', ['id' => $notificationRecepient->id]);
    }
}
