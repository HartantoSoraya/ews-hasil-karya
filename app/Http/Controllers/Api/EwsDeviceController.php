<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEwsDeviceRequest;
use App\Http\Requests\UpdateEwsDeviceRequest;
use App\Http\Resources\EwsDeviceResource;
use App\Interfaces\EwsDeviceRepositoryInterface;
use Illuminate\Http\Request;

class EwsDeviceController extends Controller
{
    protected $EwsDeviceRepository;

    public function __construct(EwsDeviceRepositoryInterface $EwsDeviceRepository)
    {
        $this->EwsDeviceRepository = $EwsDeviceRepository;

        $this->middleware(['permission:ews-device-list|ews-device-create|ews-device-edit|ews-device-delete'], [
            'only' => ['index', 'show'],
        ]);
        $this->middleware(['permission:ews-device-create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:ews-device-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:ews-device-delete'], ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        try {
            $client_id = $request->input('client_id');

            $ewsDevices = $this->EwsDeviceRepository->getAllEwsDevices($client_id);

            return ResponseHelper::jsonResponse(true, 'Success', EwsDeviceResource::collection($ewsDevices), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function store(StoreEwsDeviceRequest $request)
    {
        $request = $request->validated();

        $code = $request['code'];
        if ($code == 'AUTO') {
            do {
                $code = $this->EwsDeviceRepository->generateCode();
            } while (! $this->EwsDeviceRepository->isUniqueCode($code));
            $request['code'] = $code;
        }

        try {
            $ewsDevice = $this->EwsDeviceRepository->create($request);

            return ResponseHelper::jsonResponse(true, 'Data Alat EWS berhasil ditambahkan.', new EwsDeviceResource($ewsDevice), 201);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function show($id)
    {
        try {
            $ewsDevice = $this->EwsDeviceRepository->getEwsDeviceById($id);

            return ResponseHelper::jsonResponse(true, 'Success', new EwsDeviceResource($ewsDevice), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function update(UpdateEwsDeviceRequest $request, $id)
    {
        $request = $request->validated();

        $code = $request['code'];
        if ($code == 'AUTO') {
            do {
                $code = $this->EwsDeviceRepository->generateCode();
            } while (! $this->EwsDeviceRepository->isUniqueCode($code, $id));
            $request['code'] = $code;
        }

        try {
            $ewsDevice = $this->EwsDeviceRepository->update($request, $id);

            return ResponseHelper::jsonResponse(true, 'Data Alat EWS berhasil diubah.', new EwsDeviceResource($ewsDevice), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function destroy($id)
    {
        try {
            $ewsDevice = $this->EwsDeviceRepository->delete($id);

            return ResponseHelper::jsonResponse(true, 'Data Alat EWS berhasil dihapus.', new EwsDeviceResource($ewsDevice), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
