<?php

namespace App\Services\Api\V1;

use App\Helpers\Constant;
use App\Models\Cart;
use App\Models\CategoriesMedia;
use App\Models\Category;
use App\Models\Eula;
use App\Models\Infographic;
use App\Models\InfoUrl;
use App\Models\Lookup;
use App\Models\Solution;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GeneralService extends BaseService
{
    public function getUserTypes($request = null)
    {
        return Lookup::where(['group_code' => Constant::LOOKUP_GROUP_CODE['userType'], 'status' => true])->get();
    }
    public function getContactUsReasons($request = null)
    {
        return Lookup::where(['group_code' => Constant::LOOKUP_GROUP_CODE['contactReason'], 'status' => true])->get();
    }
    public function getInfoUrls($request = null)
    {
        return InfoUrl::where(['status' => true])->get();
    }

    public function getFirstInfographic($request = null)
    {
        return Infographic::first();
    }

    public function getFirstEULA($request = null)
    {
        return Eula::first();
    }
}
