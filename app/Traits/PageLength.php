<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

trait PageLength
{

    protected function webPageSize()
    {
        return config('web_per_page', 15);
    }

    protected function apiPageLengthSm()
    {
        return config('api_per_page', 15);
    }

    protected function apiPageLengthMd()
    {
        return config('api_per_page', 15);
    }
    protected function apiPageLengthLg()
    {
        return config('api_per_page', 15);
    }

    protected function apiPageLengthXl()
    {
        return config('api_per_page', 15);
    }
}
