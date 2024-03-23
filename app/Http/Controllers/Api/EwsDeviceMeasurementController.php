<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEwsDeviceMeasurementRequest;
use App\Http\Requests\UpdateEwsDeviceMeasurementRequest;
use App\Http\Resources\EwsDeviceMeasurementResource;
use App\Interfaces\EwsDeviceMeasurementRepositoryInterface;
use App\Models\EwsDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EwsDeviceMeasurementController extends Controller
{
    protected $EwsDeviceMeasurementRepository;

    public function __construct(EwsDeviceMeasurementRepositoryInterface $EwsDeviceMeasurementRepository)
    {
        $this->EwsDeviceMeasurementRepository = $EwsDeviceMeasurementRepository;
    }

    public function index(Request $request)
    {
        try {
            $ewsDeviceMeasurements = $this->EwsDeviceMeasurementRepository->getAllEwsDeviceMeasurements();

            return ResponseHelper::jsonResponse(true, 'Success', EwsDeviceMeasurementResource::collection($ewsDeviceMeasurements), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function store(Request $request)
    {
        $request = $request->all();

        try {
            $request['ews_device_id'] = EwsDevice::where('code', '=', $request['device_code'])->first()->id;

            $ewsDeviceMeasurement = $this->EwsDeviceMeasurementRepository->create($request);

            Log::info('Data pengukuran ews berhasil ditambahkan.', ['data' => $ewsDeviceMeasurement]);

            return ResponseHelper::jsonResponse(true, 'Data pengukuran ews berhasil ditambahkan.', new EwsDeviceMeasurementResource($ewsDeviceMeasurement), 201);
        } catch (\Exception $e) {

            Log::error('Data pengukuran ews gagal ditambahkan.', ['error' => $e->getMessage()]);

            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function show($id)
    {
        try {
            $ewsDeviceMeasurement = $this->EwsDeviceMeasurementRepository->getEwsDeviceMeasurementById($id);

            if (!$ewsDeviceMeasurement) {
                return ResponseHelper::jsonResponse(false, 'Data pengukuran ews tidak ditemukan.', null, 404);
            }

            return ResponseHelper::jsonResponse(true, 'Success', new EwsDeviceMeasurementResource($ewsDeviceMeasurement), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function update(UpdateEwsDeviceMeasurementRequest $request, $id)
    {
        $request = $request->validated();

        try {
            $request['ews_device_id'] = EwsDevice::where('code', '=', $request['device_code'])->first()->id;

            $ewsDeviceMeasurement = $this->EwsDeviceMeasurementRepository->update($request, $id);

            return ResponseHelper::jsonResponse(true, 'Data pengukuran ews berhasil diubah.', new EwsDeviceMeasurementResource($ewsDeviceMeasurement), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function destroy($id)
    {
        try {
            $ewsDeviceMeasurement = $this->EwsDeviceMeasurementRepository->delete($id);

            return ResponseHelper::jsonResponse(true, 'Data pengukuran ews berhasil dihapus.', new EwsDeviceMeasurementResource($ewsDeviceMeasurement), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
