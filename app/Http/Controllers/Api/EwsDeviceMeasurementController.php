<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateEwsDeviceMeasurementRequest;
use App\Http\Resources\EwsDeviceMeasurementResource;
use App\Interfaces\EwsDeviceMeasurementRepositoryInterface;
use App\Interfaces\EwsDeviceRepositoryInterface;
use App\Models\EwsDevice;
use App\Models\EwsDeviceMeasurement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EwsDeviceMeasurementController extends Controller
{
    protected $EwsDeviceMeasurementRepository;
    protected $EwsDeviceRepository;


    public function __construct(EwsDeviceMeasurementRepositoryInterface $EwsDeviceMeasurementRepository, EwsDeviceRepositoryInterface $EwsDeviceRepository)
    {
        $this->EwsDeviceMeasurementRepository = $EwsDeviceMeasurementRepository;
        $this->EwsDeviceRepository = $EwsDeviceRepository;
    }

    public function index(Request $request)
    {
        try {
            $ews_device_id = $request->input('ews_device_id');
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');

            $ewsDeviceMeasurements = $this->EwsDeviceMeasurementRepository->getAllEwsDeviceMeasurements(
                $ews_device_id,
                $start_date,
                $end_date
            );

            return ResponseHelper::jsonResponse(true, 'Success', EwsDeviceMeasurementResource::collection($ewsDeviceMeasurements), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function store(Request $request)
    {
        $request = $request->all();

        Log::info($request);

        try {
            $request['ews_device_id'] = EwsDevice::where('code', '=', $request['device_code'])->first()->id;

            $ewsDeviceMeasurement = $this->EwsDeviceMeasurementRepository->create($request);

            return ResponseHelper::jsonResponse(true, 'Data pengukuran ews berhasil ditambahkan.', new EwsDeviceMeasurementResource($ewsDeviceMeasurement), 201);
        } catch (\Exception $e) {

            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function show($id)
    {
        try {
            $ewsDeviceMeasurement = $this->EwsDeviceMeasurementRepository->getEwsDeviceMeasurementById($id);

            if (! $ewsDeviceMeasurement) {
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

    public function chart(Request $request)
    {
        try {
            $device = $this->EwsDeviceRepository->getEwsDeviceByDeviceCode($request->code);

            $latestMeasurement = EwsDeviceMeasurement::where('ews_device_id', $device->id)
                ->latest('created_at')
                ->first();

            if ($latestMeasurement) {
                $chartData = [
                    [
                        'vibration_value' => $latestMeasurement->vibration_value,
                        'db_value' => $latestMeasurement->db_value,
                        'time' => $latestMeasurement->created_at->format('Y-m-d H:i:s'),
                    ],
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
