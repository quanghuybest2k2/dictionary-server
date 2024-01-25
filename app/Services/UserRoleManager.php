<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class UserRoleManager
{
    private $currentUserRole;

    public function __construct()
    {
        $this->currentUserRole = Auth::check() ? Auth::user()->role_as : 'Guest';
    }

    public function getCurrentUserRole()
    {
        return $this->currentUserRole;
    }
}
