<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNotificationRecepientRequest;
use App\Http\Requests\UpdateNotificationRecepientRequest;
use App\Http\Resources\NotificationRecepientResource;
use App\Interfaces\NotificationRecepientRepositoryInterface;
use Illuminate\Http\Request;

class NotificationRecepientController extends Controller
{
    protected $notificationRecepientRepository;

    public function __construct(NotificationRecepientRepositoryInterface $notificationRecepientRepository)
    {
        $this->notificationRecepientRepository = $notificationRecepientRepository;
    }

    public function index(Request $request)
    {
        try {
            $notificationRecepients = $this->notificationRecepientRepository->getAllNotificationRecepients();

            return ResponseHelper::jsonResponse(true, 'Success', NotificationRecepientResource::collection($notificationRecepients), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function store(StoreNotificationRecepientRequest $request)
    {
        $request = $request->validated();

        try {
            $notificationRecepient = $this->notificationRecepientRepository->createNotificationRecepient($request);

            return ResponseHelper::jsonResponse(true, 'Data penerima notifikasi berhasil ditambahkan.', new NotificationRecepientResource($notificationRecepient), 201);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function show($id)
    {
        try {
            $notificationRecepient = $this->notificationRecepientRepository->getNotificationRecepientById($id);

            if (! $notificationRecepient) {
                return ResponseHelper::jsonResponse(false, 'Data penerima notifikasi tidak ditemukan.', null, 404);
            }

            return ResponseHelper::jsonResponse(true, 'Success', new NotificationRecepientResource($notificationRecepient), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function update(UpdateNotificationRecepientRequest $request, $id)
    {
        $request = $request->validated();

        try {
            $notificationRecepient = $this->notificationRecepientRepository->updateNotificationRecepient($request, $id);

            return ResponseHelper::jsonResponse(true, 'Data penerima notifikasi berhasil diperbarui.', new NotificationRecepientResource($notificationRecepient), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function destroy($id)
    {
        try {
            $notificationRecepient = $this->notificationRecepientRepository->deleteNotificationRecepient($id);

            return ResponseHelper::jsonResponse(true, 'Data penerima notifikasi berhasil dihapus.', new NotificationRecepientResource($notificationRecepient), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
