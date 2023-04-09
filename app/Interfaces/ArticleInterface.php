<?php

namespace App\Interfaces;

interface ArticleInterface {
    public function getArticles($request);
    public function getFilters();
    public function saveUserPreferences($request);
}
