<?php

namespace App\Interfaces\Repositories;

interface IReserveRepository
{
    public function createOrUpdateReserve($data);
    public function getReserve();
    public function getReserveById($id);
    public function getRoomWithoutReserve($begin, $end);
    public function getReservesByLesseeId($id);
    public function getRequestsByResponsableID($id);

}
