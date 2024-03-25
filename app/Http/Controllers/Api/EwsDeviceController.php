<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEwsDeviceRequest;
use App\Http\Requests\UpdateEwsDeviceRequest;
use App\Http\Resources\EwsDeviceResource;
use App\Interfaces\EwsDeviceRepositoryInterface;
use App\Models\EwsDeviceMeasurement;
use Illuminate\Http\Request;

class EwsDeviceController extends Controller
{
    protected $EwsDeviceRepository;

    public function __construct(EwsDeviceRepositoryInterface $EwsDeviceRepository)
    {
        $this->EwsDeviceRepository = $EwsDeviceRepository;
    }

    public function index(Request $request)
    {
        try {
            $ewsDevices = $this->EwsDeviceRepository->getAllEwsDevices($request->all());

            $anu = EwsDeviceResource::collection($ewsDevices);

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
            $tryCount = 0;
            do {
                $code = $this->EwsDeviceRepository->generateCode($tryCount);
                $tryCount++;
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
            $tryCount = 0;
            do {
                $code = $this->EwsDeviceRepository->generateCode($tryCount);
                $tryCount++;
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

    public function chart(Request $request)
{
    try {
        $device = $this->EwsDeviceRepository->getEwsDeviceByDeviceCode($request->code);

        $latestMeasurement = EwsDeviceMeasurement::where('ews_device_id', $device->id)
            ->latest('created_at')
            ->first();

        // Return the latest measurement
        if ($latestMeasurement) {
            $chartData = [
                [
                    'vibration_value' => $latestMeasurement->vibration_value,
                    'db_value' => $latestMeasurement->db_value,
                    'time' => $latestMeasurement->created_at->format('Y-m-d H:i:s'),
                ]
            ];
            return ResponseHelper::jsonResponse(true, 'Success', $chartData, 200);
        } else {
            return ResponseHelper::jsonResponse(true, 'No measurements found', [], 200);
        }
    } catch (\Exception $e) {
        return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
    }
}

}
