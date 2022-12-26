<?php

namespace App\Services\Web;

use App\Models\Eula;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class EulaService extends BaseService
{

    public function getEulasList($request)
    {
        $columnArray = ['id', 'name',];
        $sortOptions = ['desc', 'asc'];
        $derivedColArray = [];
        $derivedColKeys = [];

        $query = Eula::query();


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

    public function saveEula($data)
    {
        DB::beginTransaction();

        $eulaData = [
            'name' => $data['name'],
            'status' => true
        ];


        if (isset($data['file'])) {
            if (is_array($data['file'])) {
            } else {
                $eulaData['file_url'] = $data['file'];
            }
        } else {
            $eulaData['file_url'] = null;
        }

        if($eulaInDB = Eula::first()) {
            $eulaInDB->name = $eulaData['name'];
            $eulaInDB->file_url = $eulaData['file_url'];
            $eulaInDB->save();
            DB::commit();
            return $eulaInDB;
        } else {
            return null;
        }

    }
}
