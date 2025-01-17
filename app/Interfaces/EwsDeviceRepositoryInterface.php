<?php

namespace App\Interfaces;

interface EwsDeviceRepositoryInterface
{
    public function getAllEwsDevices();

    public function create(array $data);

    public function getEwsDeviceById(string $id);

    public function getEwsDeviceByDeviceCode(string $code);

    public function update(array $data, string $id);

    public function delete(string $id);

    public function generateCode(): string;

    public function isUniqueCode(string $code, $exceptId = null): bool;
}
