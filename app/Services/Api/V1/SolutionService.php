<?php

namespace App\Services\Api\V1;

use App\Models\Solution;
use App\Models\SolutionsMedia;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class SolutionService extends BaseService
{

    public function getSolutionList($request, $api = false)
    {
        $columnArray = ['id', 'name', 'published'];
        $ascArray = ['desc', 'asc'];
        $derivedColArray = [];
        $derivedColKeys = [];

        $query = Solution::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->sub_category_id) {
            $query = $query->where('sub_category_id', $request->sub_category_id);
        }

        if ($api) {
            $query = $query->where('status',  1);
        } else {
            if ($request->status != '') {
                $query->where('status', $request->status);
            }
        }

        if ($request->column && $request->dir && in_array($request->column, $columnArray) && in_array($request->dir, $ascArray)) {
            $query = $query->orderBy($request->column,  $request->dir);
        } else {
            $query = $query->orderBy('id',  'asc');
        }

        $pageSize = 10;
        if ($request->length) {
            $pageSize = $request->length;
        }

        $data = $query->paginate($pageSize);
        return $data;
    }

    public function saveSolution($data)
    {
        DB::beginTransaction();

        $solutionData = array(
            'name' => $data['name'],
            'description' => $data['description'],
            'published' => $data['published'],
            'sub_category_id' => $data['sub_category_id']
        );

        $solution = Solution::create($solutionData);

        if (isset($data['image'])) {
            foreach ($data['image'] as $image) {
                $mediaData[] = array('media_url' => $image['url'], 'solution_id' => $solution->id);
            }
            if (isset($mediaData) && isset($solution)) {
                SolutionsMedia::insert($mediaData);
            }
        }

        DB::commit();

        return $solution;
    }
}
