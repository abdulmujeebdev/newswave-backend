<?php

namespace App\Interfaces;

interface ArticleInterface {
    public function getArticles($request);
    public function getFilters($request);
    public function saveUserPreferences($request);

    public function getUserPreferences($request);
}
