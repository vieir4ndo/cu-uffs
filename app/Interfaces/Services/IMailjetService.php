<?php
namespace App\Interfaces\Services;

interface IMailjetService
{
    function send($email, $name, $subject, $message);
}
