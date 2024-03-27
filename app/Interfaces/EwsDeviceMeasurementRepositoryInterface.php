<?php

namespace App\Interfaces;

interface EwsDeviceMeasurementRepositoryInterface
{
    public function getAllEwsDeviceMeasurements($ews_device_id = null, $start_date = null, $end_date = null);

    public function create(array $data);

    public function getEwsDeviceMeasurementById(string $id);

    public function update(array $data, string $id);

    public function delete(string $id);
}
