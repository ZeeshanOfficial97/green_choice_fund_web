<?php

namespace App\Services\Web;

use App\Helpers\Constant;
use App\Models\CategoriesMedia;
use App\Models\Category;
use App\Models\InstitutionInquiry;
use App\Models\Lookup;
use App\Models\Solution;
use App\Models\SolutionsMedia;
use App\Models\User;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class CategoryService extends BaseService
{

    public function getCategoriesList($request)
    {
        $columnArray = ['id', 'name',];
        $sortOptions = ['desc', 'asc'];
        $derivedColArray = [];
        $derivedColKeys = [];

        $query = Category::query();


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

    public function getCategoriesDDLList()
    {
        $data = Category::where("status", "=", 1)->get(['name AS label', 'id as value'])->toArray();
        return $data;
    }

    public function saveCategory($data)
    {
        DB::beginTransaction();

        $categoryData = [
            'name' => $data['name'],
            'published' => isset($data['published']) && $data['published'] == 'true' ? true : false,
            'status' => isset($data['status']) && $data['status'] == 'true' ? true : false,
        ];

        $category = Category::create($categoryData);

        if (isset($data['image'])) {
            if (is_array($data['image'])) {
                foreach ($data['image'] as $image) {
                    $mediaData[] = array('media_url' => $image['url'], 'category_id' => $category->id, 'media_type' => $data['media_type']);
                }    
            } else {
                $mediaData[] = array('media_url' => $data['image'], 'category_id' => $category->id, 'media_type' => $data['media_type']);
            }

            if (isset($data) && isset($category)) {
                CategoriesMedia::insert($mediaData);
            }
        }

        DB::commit();
        return $category;
    }

    public function updateCategory($categoryInDb,$data)
    {
        DB::beginTransaction();

        $categoryInDb->name = $data['name'];
        $categoryInDb->published = isset($data['published']) && $data['published'] == 'true' ? true : false;
        $categoryInDb->status = isset($data['status']) && $data['status'] == 'true' ? true : false;

        $categoryInDb->save();

        if (isset($data['image'])) {
            CategoriesMedia::where(['category_id' => $categoryInDb->id])->delete();
            if (is_array($data['image'])) {
                foreach ($data['image'] as $image) {
                    $mediaData[] = array('media_url' => $image['url'], 'category_id' => $categoryInDb->id, 'media_type' => $data['media_type']);
                }    
            } else {
                $mediaData[] = array('media_url' => $data['image'], 'category_id' => $categoryInDb->id, 'media_type' => $data['media_type']);
            }

            if (isset($data) && isset($categoryInDb)) {
                CategoriesMedia::insert($mediaData);
            }
        }

        DB::commit();
        return $categoryInDb;
    }
}
