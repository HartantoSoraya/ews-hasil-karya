<?php

namespace App\Interfaces;

interface notificationRecepientRepositoryInterface
{
    public function getAllNotificationRecepients();

    public function getNotificationRecepientById(string $id);

    public function createNotificationRecepient(array $data);

    public function updateNotificationRecepient(array $data, string $id);

    public function deleteNotificationRecepient(string $id);
}            