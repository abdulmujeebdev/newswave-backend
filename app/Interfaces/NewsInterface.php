<?php

namespace App\Interfaces;

interface NewsInterface {
    public function fetchNews() : array;
    public function parseNews($result) : array;
    public function store($articles) : array;
}





