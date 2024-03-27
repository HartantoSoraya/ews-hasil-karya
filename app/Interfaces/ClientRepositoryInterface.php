<?php

namespace App\Interfaces;

interface clientRepositoryInterface
{
    public function getAllClients();

    public function getClientById(string $id);

    public function createClient(array $data);

    public function updateClient(array $data, string $id);

    public function updateActiveStatus(string $id, bool $status);

    public function deleteClient(string $id);

    public function generateCode(int $tryCount): string;

    public function isUniqueCode(string $code, $exceptId = null): bool;
}
