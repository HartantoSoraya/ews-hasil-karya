<?php

namespace App\Repositories;

use App\Interfaces\EwsDeviceMeasurementRepositoryInterface;
use App\Models\EwsDeviceMeasurement;

class EwsDeviceMeasurementRepository implements EwsDeviceMeasurementRepositoryInterface
{
    public function getAllEwsDeviceMeasurements($ews_device_id = null, $start_date = null, $end_date = null)
    {
        $ewsDeviceMeasurements = EwsDeviceMeasurement::orderBy('created_at', 'desc');

        if ($ews_device_id !== null) {
            $ewsDeviceMeasurements->where('ews_device_id', $ews_device_id);
        }

        if ($start_date !== null) {
            $ewsDeviceMeasurements->whereDate('created_at', '>=', $start_date);
        }

        if ($end_date !== null) {
            $ewsDeviceMeasurements->whereDate('created_at', '<=', $end_date);
        }

        $ewsDeviceMeasurements = $ewsDeviceMeasurements->get();

        return $ewsDeviceMeasurements;
    }

    public function create(array $data)
    {
        $ewsDeviceMeasurement = new EwsDeviceMeasurement();
        $ewsDeviceMeasurement->ews_device_id = $data['ews_device_id'];
        $ewsDeviceMeasurement->vibration_value = $data['vibration_value'];
        $ewsDeviceMeasurement->db_value = $data['db_value'];
        $ewsDeviceMeasurement->save();

        return $ewsDeviceMeasurement;
    }

    public function getEwsDeviceMeasurementById(string $id)
    {
        $ewsDeviceMeasurement = EwsDeviceMeasurement::find($id);

        return $ewsDeviceMeasurement;
    }

    public function update(array $data, string $id)
    {
        $ewsDeviceMeasurement = EwsDeviceMeasurement::find($id);
        $ewsDeviceMeasurement->ews_device_id = $data['ews_device_id'];
        $ewsDeviceMeasurement->vibration_value = $data['vibration_value'];
        $ewsDeviceMeasurement->db_value = $data['db_value'];
        $ewsDeviceMeasurement->save();

        return $ewsDeviceMeasurement;
    }

    public function delete(string $id)
    {
        $ewsDeviceMeasurement = EwsDeviceMeasurement::find($id);
        $ewsDeviceMeasurement->delete();

        return $ewsDeviceMeasurement;
    }
}
