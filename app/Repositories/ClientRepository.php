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
            $user = new User();
            $user->email = $data['email'];
            $user->password = bcrypt($data['password']);
            $user->save();
            $user->assignRole(UserRoleEnum::CLIENT_USER);

            $client = new Client();
            $client->user_id = $user->id;
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

            foreach ($data['ews_devices'] as $ewsDeviceId) {
                $client->ewsDevices()->attach($ewsDeviceId);
            }

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

            $client->ewsDevices()->detach();
            foreach ($data['ews_devices'] as $ewsDeviceId) {
                $client->ewsDevices()->attach($ewsDeviceId);
            }

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
        $count = Client::count() + 1 + $tryCount;
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
