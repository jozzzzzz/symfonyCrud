<?php
namespace App\Service;

class UserStatusService
{
    private $status;

    public function __construct()
    {
        $this->status = false;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }
}
