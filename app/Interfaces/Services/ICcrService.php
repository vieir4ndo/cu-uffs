<?php

namespace App\Interfaces\Services;

interface ICcrService
{
    public function createCcr($data);
    public function updateCcr($data, $id);
    public function getCcr();
    public function getCcrById($id);
}
