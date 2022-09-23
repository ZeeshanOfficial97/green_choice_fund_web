<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use App\Traits\AttachableMedia;
use App\Traits\RequestHelper;
use App\Traits\PageLength;

class ApiController extends Controller
{
    use ApiResponse, RequestHelper, PageLength, AttachableMedia;
}
