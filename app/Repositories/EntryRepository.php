<?php

namespace App\Repositories;

use App\Interfaces\Repositories\IEntryRepository;
use App\Models\Entry;

class EntryRepository implements IEntryRepository
{
    public function insert($data){
        return Entry::create($data);
    }

    public function getEntriesById(string $id){
        return Entry::select('date_time')->where("user_id", $id)->get();
    }
}
