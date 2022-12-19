<?php

namespace App\Services\Web;

use App\Models\Solution;
use App\Models\SolutionsMedia;
use App\Models\User;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class AppUserService extends BaseService
{

    public function getAppUsersList($request)
    {
        $columnArray = ['id', 'name', 'email'];
        $sortOptions = ['desc', 'asc'];
        $derivedColArray = [];
        $derivedColKeys = [];

        $query = User::whereHas('roles', function ($query) {
            $query->where('name', 'app_user');
        });

        if ($request->q) {
            $query->where('name', 'like', '%' . $request->q . '%')
                ->where('email', 'like', '%' . $request->q . '%')
                ->where('stripe_user_id', 'like', '%' . $request->q . '%')
                ->where('country_code', 'like', '%' . $request->q . '%')
                ->where('contact_no', 'like', '%' . $request->q . '%');
        }
        if ($request->userTypeId) {
            $query->where('user_type_id', $request->userTypeId);
        }

        if ($request->status != '' && $request->status != null) {
            $query->where('status', $request->status);
        }



        if ($request->sortBy && $request->dir && in_array($request->sortBy, $columnArray) && in_array($request->dir, $sortOptions)) {
            $query = $query->orderBy($request->sortBy,  $request->dir);
        } else {
            $query = $query->orderBy('id',  'desc');
        }

        $pageSize = 10;
        if ($request->length) {
            $pageSize = $request->length;
        }

        $data = $query->paginate($pageSize);
        return $data;
    }

}
