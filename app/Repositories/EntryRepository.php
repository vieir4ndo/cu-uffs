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
        return Entry::select('date_time')->where("user_id", $id)->simplePaginate(15);
    }

    public function getEntriesInInterval($init_date, $final_date){
        return Entry::where("date_time", ">", $init_date)->where("date_time", "<", $final_date)->get();
    }

    public function getEntriesByDate($date){
        return Entry::where("date_time", $date)->get();
    }

    public function getLastEntryById(string $id){
        return Entry::where("user_id", $id)->orderBy('date_time', 'desc')->first();
    }
}
