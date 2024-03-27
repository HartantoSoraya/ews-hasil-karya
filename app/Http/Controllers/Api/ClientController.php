<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Http\Resources\ClientResource;
use App\Interfaces\ClientRepositoryInterface;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    protected $clientRepository;

    public function __construct(ClientRepositoryInterface $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function index(Request $request)
    {
        try {
            $clients = $this->clientRepository->getAllClients();

            return ResponseHelper::jsonResponse(true, 'Success', ClientResource::collection($clients), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function store(StoreClientRequest $request)
    {
        $request = $request->validated();

        $code = $request['code'];
        if ($code == 'AUTO') {
            $tryCount = 0;
            do {
                $code = $this->clientRepository->generateCode($tryCount);
                $tryCount++;
            } while (! $this->clientRepository->isUniqueCode($code));
            $request['code'] = $code;
        }

        try {
            $client = $this->clientRepository->createClient($request);

            return ResponseHelper::jsonResponse(true, 'Data pelanggan berhasil ditambahkan.', new ClientResource($client), 201);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function show($id)
    {
        try {
            $client = $this->clientRepository->getClientById($id);

            if (! $client) {
                return ResponseHelper::jsonResponse(false, 'Data pelanggan tidak ditemukan.', null, 404);
            }

            return ResponseHelper::jsonResponse(true, 'Success', new ClientResource($client), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);

        }
    }

    public function update(UpdateClientRequest $request, $id)
    {
        $request = $request->validated();

        $code = $request['code'];
        if ($code == 'AUTO') {
            $tryCount = 0;
            do {
                $code = $this->clientRepository->generateCode($tryCount);
                $tryCount++;
            } while (! $this->clientRepository->isUniqueCode($code, $id));
            $request['code'] = $code;
        }

        try {
            $client = $this->clientRepository->getClientById($id);
            if (! $client) {
                return ResponseHelper::jsonResponse(false, 'Data pelanggan tidak ditemukan.', null, 404);
            }

            $client = $this->clientRepository->updateClient($request, $id);

            return ResponseHelper::jsonResponse(true, 'Data pelanggan berhasil diubah.', new ClientResource($client), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function updateActiveStatus(Request $request, $id)
    {
        $request = $request->validate([
            'status' => 'required|boolean',
        ]);

        try {
            $client = $this->clientRepository->getClientById($id);
            if (! $client) {
                return ResponseHelper::jsonResponse(false, 'Data pelanggan tidak ditemukan.', null, 404);
            }

            $client = $this->clientRepository->updateActiveStatus($id, $request['status']);

            $message = $client->is_active ? 'Pelanggan berhasil diaktifkan.' : 'Pelanggan berhasil dinonaktifkan.';

            return ResponseHelper::jsonResponse(true, $message, new ClientResource($client), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function destroy($id)
    {
        try {
            $client = $this->clientRepository->getClientById($id);
            if (! $client) {
                return ResponseHelper::jsonResponse(false, 'Data pelanggan tidak ditemukan.', null, 404);
            }

            $this->clientRepository->deleteClient($id);

            return ResponseHelper::jsonResponse(true, 'Data pelanggan berhasil dihapus.', null, 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
