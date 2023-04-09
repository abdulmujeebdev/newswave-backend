<?php

namespace App\Interfaces;

interface AuthInterface {
    public function login($request);
    public function register($request);
    public function updateProfile($request);
}





