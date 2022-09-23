<?php

namespace App\Services\Api\V1;

use App\Models\SubCategoriesMedia;
use App\Models\SubCategory;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class SubCategoryService extends BaseService
{

    public function getSubCategoryList($request, $api = false)
    {
        $columnArray = ['id', 'name', 'published'];
        $ascArray = ['desc', 'asc'];

        $query = SubCategory::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->category_id) {
            $query = $query->where('category_id', $request->category_id);
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


    public function saveSubCategory($data)
    {
        DB::beginTransaction();

        $subCategoryData = array(
            'name' => $data['name'],
            'description' => $data['description'],
            'published' => $data['published'],
            'category_id' => $data['category_id']
        );

        $subCategory = SubCategory::create($subCategoryData);

        if (isset($data['image'])) {
            foreach ($data['image'] as $image) {
                $mediaData[] = array('image_url' => $image['url'], 'sub_category_id' => $subCategory->id);
            }
            if (isset($mediaData) && isset($subCategory)) {
                SubCategoriesMedia::insert($mediaData);
            }
        }


        DB::commit();

        return $subCategory;
    }


}
