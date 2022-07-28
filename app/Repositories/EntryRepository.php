<?php

namespace App\Repositories;

use App\Models\Entry;

class EntryRepository
{
    public function insert($data){
        return Entry::create($data);
    }

    public function getEntriesById(string $id){
        return Entry::select('date_time')->where("user_id", $id)->get();
    }
}
