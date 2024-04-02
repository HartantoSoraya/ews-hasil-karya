<?php

namespace App\Repositories;

use App\Enum\UserRoleEnum;
use App\Interfaces\ClientRepositoryInterface;
use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ClientRepository implements ClientRepositoryInterface
{
    public function getAllClients()
    {
        $clients = Client::with('user', 'ewsDevices')
            ->orderBy('created_at', 'desc')
            ->get();

        return $clients;
    }

    public function getClientById(string $id)
    {
        return Client::findOrFail($id);
    }

    public function createClient(array $data)
    {
        DB::beginTransaction();

        try {
            $user = User::create([
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
            ]);

            $user->assignRole(UserRoleEnum::CLIENT);

            $client = Client::create([
                'user_id' => $user->id,
                'code' => $data['code'],
                'name' => $data['name'],
                'province' => $data['province'],
                'regency' => $data['regency'],
                'district' => $data['district'],
                'subdistrict' => $data['subdistrict'],
                'address' => $data['address'],
                'phone' => $data['phone'],
                'is_active' => $data['is_active'],
            ]);

            $client->ewsDevices()->attach($data['ews_devices']);

            DB::commit();

            return $client;
        } catch (\Exception $e) {
            DB::rollBack();

            return $e->getMessage();
        }
    }

    public function updateClient(array $data, string $id)
    {
        DB::beginTransaction();

        try {
            $client = Client::findOrFail($id);
            $client->code = $data['code'];
            $client->name = $data['name'];
            $client->province = $data['province'];
            $client->regency = $data['regency'];
            $client->district = $data['district'];
            $client->subdistrict = $data['subdistrict'];
            $client->address = $data['address'];
            $client->phone = $data['phone'];
            $client->is_active = $data['is_active'];
            $client->save();

            if (isset($data['password'])) {
                $user = User::findOrFail($client->user_id);
                $user->password = bcrypt($data['password']);
                $user->save();
            }

            $client->ewsDevices()->sync($data['ews_devices']);

            DB::commit();

            return $client;
        } catch (\Exception $e) {
            DB::rollBack();

            return $e->getMessage();
        }
    }

    public function updateActiveStatus(string $id, bool $status)
    {
        DB::beginTransaction();

        try {
            $client = Client::findOrFail($id);
            $client->is_active = $status;
            $client->save();

            DB::commit();

            return $client;
        } catch (\Exception $e) {
            DB::rollBack();

            return $e->getMessage();
        }
    }

    public function deleteClient(string $id)
    {
        DB::beginTransaction();

        try {
            Client::findOrFail($id)->delete();

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();

            return $e->getMessage();
        }
    }

    public function generateCode(int $tryCount): string
    {
        $count = Client::withTrashed()->count() + 1 + $tryCount;
        $code = 'CL'.str_pad($count, 3, '0', STR_PAD_LEFT);

        return $code;
    }

    public function isUniqueCode(string $code, $exceptId = null): bool
    {
        $query = Client::where('code', $code);

        if ($exceptId) {
            $query->where('id', '!=', $exceptId);
        }

        return $query->doesntExist();
    }
}
