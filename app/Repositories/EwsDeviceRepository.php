<?php

namespace App\Repositories;

use App\Interfaces\EwsDeviceRepositoryInterface;
use App\Models\EwsDevice;
use App\Models\EwsDeviceAddress;

class EwsDeviceRepository implements EwsDeviceRepositoryInterface
{
    public function getAllEwsDevices()
    {
        $getAllEwsDevices = EwsDevice::orderBy('name', 'asc')->get();

        return $getAllEwsDevices;
    }

    public function create(array $data)
    {
        $ewsDevice = new EwsDevice();
        $ewsDevice->code = $data['code'];
        $ewsDevice->name = $data['name'];
        $ewsDevice->type = $data['type'];
        $ewsDevice->save();

        for ($i = 0; $i < count($data['addresses']); $i++) {
            $ewsDeviceAddress = new EwsDeviceAddress();
            $ewsDeviceAddress->ews_device_id = $ewsDevice->id;
            $ewsDeviceAddress->address = $data['addresses'][$i]['address'];
            $ewsDeviceAddress->save();
        }

        return $ewsDevice;
    }

    public function getEwsDeviceById(string $id)
    {
        $ewsDevice = EwsDevice::find($id);

        return $ewsDevice;
    }

    public function getEwsDeviceByDeviceCode(string $code)
    {
        $ewsDevice = EwsDevice::where('code', $code)->first();

        return $ewsDevice;
    }

    public function update(array $data, string $id)
    {
        $ewsDevice = EwsDevice::find($id);
        $ewsDevice->code = $data['code'];
        $ewsDevice->name = $data['name'];
        $ewsDevice->type = $data['type'];
        $ewsDevice->save();

        $ewsDevice->addresses()->delete();
        for ($i = 0; $i < count($data['addresses']); $i++) {
            $ewsDeviceAddress = new EwsDeviceAddress();
            $ewsDeviceAddress->ews_device_id = $ewsDevice->id;
            $ewsDeviceAddress->address = $data['addresses'][$i]['address'];
            $ewsDeviceAddress->save();
        }

        return $ewsDevice;
    }

    public function delete(string $id)
    {
        $ewsDevice = EwsDevice::find($id);
        $ewsDevice->delete();

        return $ewsDevice;
    }

    public function generateCode(int $tryCount): string
    {
        $count = EwsDevice::count() + 1 + $tryCount;
        $code = 'EWS'.str_pad($count, 3, '0', STR_PAD_LEFT);

        return $code;
    }

    public function isUniqueCode(string $code, $exceptId = null): bool
    {
        $query = EwsDevice::where('code', $code);
        if ($exceptId) {
            $query->where('id', '!=', $exceptId);
        }

        return $query->doesntExist();
    }
}
