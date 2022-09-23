<?php

namespace App\Services;

use App\Models\CategoriesMedia;
use App\Models\Category;
use App\Models\Solution;
use App\Models\SolutionsMedia;
use App\Models\SubCategoriesMedia;
use App\Models\SubCategory;
use Illuminate\Support\Facades\DB;

class CategoryService extends BaseService
{
    public function getCategoryList($request, $api = false)
    {
        $columnArray = ['id', 'name', 'published'];
        $ascArray = ['desc', 'asc'];
        $derivedColArray = [];
        $derivedColKeys = [];

        $query = Category::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($api) {
            $query = $query->where('status',  1);
        } else {
            if (in_array($request->status, [0, 1])) {
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


    public function saveCategory($data)
    {
        DB::beginTransaction();
        $categoryData = [
            'name' => $data['name'],
            'published' => $data['published'],
        ];

        $category = Category::create($categoryData);

        if (isset($data['image'])) {
            foreach ($data['image'] as $image) {
                $mediaData[] = array('image_url' => $image['url'], 'category_id' => $category->id);
            }

            if (isset($data) && isset($category)) {
                CategoriesMedia::insert($mediaData);
            }
        }

        DB::commit();
        return $category;
    }

    public function updateCategory($data, $category)
    {
        if (isset($data['image'])) {
            $category->image = $data['image'];
        }
        $category->name = $data['name'];
        $category->save();
        $category->categorySize()->sync(explode(",", $data['size']));
        return $category;
    }

    public function statusCategory($category)
    {
        $category->status = !$category->status;
        $category->save();
        return $category;
    }
}
