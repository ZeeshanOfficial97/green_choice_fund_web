<?php

namespace App\Services\Web;

use App\Helpers\Constant;
use App\Models\InstitutionInquiry;
use App\Models\Lookup;
use App\Models\Solution;
use App\Models\SolutionsMedia;
use App\Models\User;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class InquiryService extends BaseService
{

    public function getInquiriesList($request)
    {
        $columnArray = ['id', 'name', 'email'];
        $sortOptions = ['desc', 'asc'];
        $derivedColArray = [];
        $derivedColKeys = [];

        $query = InstitutionInquiry::query();

        if ($request->reason) {
            $name = $request->reason;
            $query->whereHas('contactReason', function ($query) use ($name) {
                $query->where('name', '=', $name);
            });
        }

        if ($request->q) {
            $query->where('name', 'like', '%' . $request->q . '%')
                ->where('email', 'like', '%' . $request->q . '%')
                ->where('country_code', 'like', '%' . $request->q . '%')
                ->where('contact_no', 'like', '%' . $request->q . '%');
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

    public function getInquiryReasonsList($request)
    {
        return Lookup::where(['group_code' => Constant::LOOKUP_GROUP_CODE['contactReason'], 'status' => true])
            ->get(['id AS value', 'name AS label'])
            ->toArray();
    }
}
