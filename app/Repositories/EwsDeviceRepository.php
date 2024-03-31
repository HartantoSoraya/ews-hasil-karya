<?php

namespace App\Repositories;

use App\Interfaces\EwsDeviceRepositoryInterface;
use App\Models\EwsDevice;
use App\Models\EwsDeviceAddressHistory;

class EwsDeviceRepository implements EwsDeviceRepositoryInterface
{
    public function getAllEwsDevices($client_id = null)
    {
        $query = EwsDevice::query();
        if ($client_id) {
            $query->whereHas('clients', function ($query) use ($client_id) {
                $query->where('client_id', $client_id);
            });
        }

        $ewsDevices = $query->orderBy('name', 'asc')->get();

        return $ewsDevices;
    }

    public function create(array $data)
    {
        $ewsDevice = new EwsDevice();
        $ewsDevice->code = $data['code'];
        $ewsDevice->name = $data['name'];
        $ewsDevice->type = $data['type'];
        $ewsDevice->province = $data['province'];
        $ewsDevice->regency = $data['regency'];
        $ewsDevice->district = $data['district'];
        $ewsDevice->subdistrict = $data['subdistrict'];
        $ewsDevice->address = $data['address'];
        $ewsDevice->description = $data['description'];
        $ewsDevice->save();

        $ewsDeviceAddressHistory = new EwsDeviceAddressHistory();
        $ewsDeviceAddressHistory->ews_device_id = $ewsDevice->id;
        $ewsDeviceAddressHistory->address = $ewsDevice->address;
        $ewsDeviceAddressHistory->save();

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
        $ewsDevice->province = $data['province'];
        $ewsDevice->regency = $data['regency'];
        $ewsDevice->district = $data['district'];
        $ewsDevice->subdistrict = $data['subdistrict'];
        $ewsDevice->address = $data['address'];
        $ewsDevice->description = $data['description'];
        $ewsDevice->save();

        $ewsDeviceAddressHistory = new EwsDeviceAddressHistory();
        $ewsDeviceAddressHistory->ews_device_id = $ewsDevice->id;
        $ewsDeviceAddressHistory->address = $ewsDevice->address;
        $ewsDeviceAddressHistory->save();

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
        $count = EwsDevice::withTrashed()->count() + 1 + $tryCount;
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
