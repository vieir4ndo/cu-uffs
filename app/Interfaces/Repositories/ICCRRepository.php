<?php

namespace App\Interfaces\Repositories;

interface ICCRRepository
{
    public function createOrUpdateCCR($data);
    public function getCCR();
    public function getCCRById($id);
}
