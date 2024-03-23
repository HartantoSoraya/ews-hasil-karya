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
            $deviceCode = $request['device_code'];
            $vibrationValue = $request['vibration_value'];
            $dbValue = $request['db_value'];

            $request['ews_device_id'] = EwsDevice::where('code', '=', $request['device_code'])->first()->id;

            $ewsDeviceMeasurement = $this->EwsDeviceMeasurementRepository->create($request);

            $messageBody = array(
                "api_key" => "1672a326944cb9c6bfef5ffbc764b5c4988752f6",
                "receiver" => "6285325483259", // Nomor penerima WhatsApp
                "data" => array("message" => "Pengukuran EWS berhasil ditambahkan.\nDevice Code: $deviceCode\nVibration Value: $vibrationValue\nDB Value: $dbValue")
            );

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://whatsapp.hasilkarya.co.id/api/send-message",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($messageBody),
                CURLOPT_HTTPHEADER => [
                    "Accept: */*",
                    "Content-Type: application/json",
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                Log::error("cURL Error #:" . $err);
            } else {
                Log::info("Response: " . $response);
            }

            return ResponseHelper::jsonResponse(true, 'Data pengukuran ews berhasil ditambahkan.', new EwsDeviceMeasurementResource($ewsDeviceMeasurement), 201);
        } catch (\Exception $e) {

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
