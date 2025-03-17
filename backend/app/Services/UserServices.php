<?php

use App\Models\User;

class UserServices
{
    public function getUsers()
    {
        return User::all();
    }
}