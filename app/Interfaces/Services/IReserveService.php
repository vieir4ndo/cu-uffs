<?php

namespace App\Interfaces\Services;

interface IReserveService
{
    public function createReserve($data);
    public function updateReserve($data, $id);
    public function deleteReserve($id);
    public function getReserve();
    public function getReserveById($id);
    public function getReservesByLesseeId($id);
    public function getRoomWithoutReserve($begin, $end);
    public function getRequestsByResponsableID($id);
}
