<?php

namespace App\Interfaces;

interface EwsDeviceMeasurementRepositoryInterface
{
    public function getAllEwsDeviceMeasurements();

    public function create(array $data);

    public function getEwsDeviceMeasurementById(string $id);

    public function update(array $data, string $id);

    public function delete(string $id);
}
