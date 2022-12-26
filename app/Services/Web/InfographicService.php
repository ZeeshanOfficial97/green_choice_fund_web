<?php

namespace App\Services\Web;

use App\Helpers\Constant;
use App\Models\CategoriesMedia;
use App\Models\Category;
use App\Models\Infographic;
use App\Models\InstitutionInquiry;
use App\Models\Lookup;
use App\Models\Solution;
use App\Models\SolutionsMedia;
use App\Models\User;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class InfographicService extends BaseService
{

    public function getInfographicsList($request)
    {
        $columnArray = ['id', 'name',];
        $sortOptions = ['desc', 'asc'];
        $derivedColArray = [];
        $derivedColKeys = [];

        $query = Infographic::query();


        if ($request->q) {
            $query->where('name', 'like', '%' . $request->q . '%');
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

    public function saveInfographic($data)
    {
        DB::beginTransaction();

        $infographicData = [
            'name' => $data['name'],
            'status' => true
        ];


        if (isset($data['file'])) {
            if (is_array($data['file'])) {
            } else {
                $infographicData['file_url'] = $data['file'];
            }
        } else {
            $infographicData['file_url'] = null;
        }

        if($infographicInDB = Infographic::first()) {
            $infographicInDB->name = $infographicData['name'];
            $infographicInDB->file_url = $infographicData['file_url'];
            $infographicInDB->save();
            DB::commit();
            return $infographicInDB;
        } else {
            return null;
        }

    }
}
