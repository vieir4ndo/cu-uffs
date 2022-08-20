<?php

namespace App\Interfaces\Repositories;

interface IEntryRepository
{
    function insert($data);
    function getEntriesById(string $id);
    function getEntriesInInterval($init_date, $final_date);
    function getEntriesByDate($date);
    function getLastEntryById(string $id);
}
