<?php

namespace App\Repositories;

use App\Interfaces\NotificationRecepientRepositoryInterface;
use App\Models\NotificationRecepient;
use Illuminate\Support\Facades\DB;

class NotificationRecepientRepository implements NotificationRecepientRepositoryInterface
{
    public function getAllNotificationRecepients()
    {
        return NotificationRecepient::all();
    }

    public function getNotificationRecepientById(string $id)
    {
        return NotificationRecepient::findOrFail($id);
    }

    public function createNotificationRecepient(array $data)
    {
        DB::beginTransaction();

        try {
            $notificationRecepient = new NotificationRecepient();
            $notificationRecepient->name = $data['name'];
            $notificationRecepient->phone_number = $data['phone_number'];
            $notificationRecepient->job_title = $data['job_title'];
            $notificationRecepient->is_active = $data['is_active'];
            $notificationRecepient->save();

            DB::commit();

            return $notificationRecepient;
        } catch (\Exception $e) {
            DB::rollBack();

            return $e->getMessage();
        }
    }

    public function updateNotificationRecepient(array $data, string $id)
    {
        DB::beginTransaction();

        try {
            $notificationRecepient = NotificationRecepient::findOrFail($id);
            $notificationRecepient->name = $data['name'];
            $notificationRecepient->phone_number = $data['phone_number'];
            $notificationRecepient->job_title = $data['job_title'];
            $notificationRecepient->is_active = $data['is_active'];
            $notificationRecepient->save();

            DB::commit();

            return $notificationRecepient;
        } catch (\Exception $e) {
            DB::rollBack();

            return $e->getMessage();
        }
    }

    public function deleteNotificationRecepient(string $id)
    {
        DB::beginTransaction();

        try {
            $notificationRecepient = NotificationRecepient::find($id);
            $notificationRecepient->delete();

            DB::commit();

            return $notificationRecepient;
        } catch (\Exception $e) {
            DB::rollBack();

            return $e->getMessage();
        }
    }
}