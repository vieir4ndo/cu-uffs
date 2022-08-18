<?php

namespace App\Interfaces\Repositories;

interface ICcrRepository
{
    public function createOrUpdateCcr($data);
    public function getCcr();
    public function getCcrById($id);
}
