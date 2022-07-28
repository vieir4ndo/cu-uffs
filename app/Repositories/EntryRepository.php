<?php

namespace App\Repositories;

use App\Models\Entry;

class EntryRepository
{
    public function insert($data){
        return Entry::create($data);
    }
}
