<?php

namespace App\Interfaces\Services;

interface ICCRService
{
    public function createCCR($data);
    public function updateCCR($data, $id);
    public function getCCR();
    public function getCCRById($id);
}
