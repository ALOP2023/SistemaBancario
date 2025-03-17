<?php

use App\Http\Controllers\Controller;

class UserController extends Controller
{
    protected $userServices;

    public function __construct(UserServices $userService)
    {
        $this->userServices = $userService;
    }

    
}