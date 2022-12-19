<?php

namespace App\Services\Web;

use App\Helpers\Constant;
use App\Models\Category;
use App\Models\InstitutionInquiry;
use App\Models\Lookup;
use App\Models\Solution;
use App\Models\SolutionsMedia;
use App\Models\User;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class SolutionService extends BaseService
{

    public function getSolutionsList($request)
    {
        $columnArray = ['id', 'name', 'published'];
        $sortOptions = ['desc', 'asc'];
        $derivedColArray = [];
        $derivedColKeys = [];

        $query = Solution::query()->with("category");

        if ($request->q) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        if ($request->category) {
            $categoryId = $request->category;
            $query->whereHas('category', function ($query) use ($categoryId) {
                $query->where('id', $categoryId);
            })->get();
        }

        if ($request->publish != '' && $request->publish != null) {
            $query->where('published', $request->publish);
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


    public function saveSolution($data)
    {
        DB::beginTransaction();

        $solutionData = array(
            'name' => $data['name'],
            'description' => $data['description'],
            'published' => isset($data['published']) && $data['published'] == 'true' ? true : false,
            'status' => isset($data['status']) && $data['published'] == 'true' ? true : false,
            'category_id' => $data['category_id']
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

    public function updateSolution($solutionInDb, $data)
    {
        DB::beginTransaction();

        $solutionInDb->name = $data['name'];
        $solutionInDb->description = $data['description'];
        $solutionInDb->category_id = $data['category_id'];
        $solutionInDb->published = isset($data['published']) && $data['published'] == 'true' ? true : false;
        $solutionInDb->status = isset($data['status']) && $data['status'] == 'true' ? true : false;

        $solutionInDb->save();

        if (isset($data['image'])) {
            if (isset($data['solution_media_id']) && is_array($data['solution_media_id'])) {
                SolutionsMedia::whereNotIn('id', $data['solution_media_id'])->delete();
            }
            if (is_array($data['image'])) {
                foreach ($data['image'] as $image) {
                    $mediaData[] = array('media_url' => $image['url'], 'solution_id' => $solutionInDb->id);
                }
            } else {
                $mediaData[] = array('media_url' => $data['image'], 'solution_id' => $solutionInDb->id);
            }

            if (isset($data) && isset($solutionInDb)) {
                SolutionsMedia::insert($mediaData);
            }
        }

        DB::commit();
        return $solutionInDb;
    }
}
