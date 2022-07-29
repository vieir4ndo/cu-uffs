<?php

namespace App\Interfaces\Repositories;

interface IEntryRepository
{
    function insert($data);
    function getEntriesById(string $id);
}
