<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;

use App\Http\Resources\Category\Api\V1\CategoryResource;
use App\Http\Resources\Category\Api\V1\CategoryResourceCollection;

use App\Models\Category;
use App\Services\Api\V1\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends ApiController
{

    /**
     * @var categoryService
     */
    private $categoryService;
    /**
     * @param categoryService $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function getCategoryList(Request $request)
    {
        $data = new CategoryResourceCollection($this->categoryService->getCategoryList($request, true));
        return $this->successResponse("Categories list", $data);
    }

    public function getCategory(Category $category)
    {
        $data = new CategoryResource($category);
        return $this->successResponse("Category", $data);
    }

    public function saveCategory(Request $request)
    {
        try {
            $data = $request->all();

            if ($files = request()->file('image')) {
                $data['image'] = $this->uploadFiles($files, 'category');
            } else {
                return $this->errorResponse("Please upload image", null, 610, "Error", null, 200);
            }

            if ($category = $this->categoryService->saveCategory($data)) {
                return $this->successResponse("New category created successfully", new CategoryResource($category));
            } else {
                return $this->errorResponse("An issue occured", null, 500, "Error", null, 500);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->exceptionResponse($th);
        }
    }

}
