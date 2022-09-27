<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;

use App\Http\Resources\SubCategory\Api\V1\SubCategoryResource;
use App\Http\Resources\SubCategory\Api\V1\SubCategoryResourceCollection;

use App\Models\SubCategory;
use App\Services\Api\V1\SubCategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubCategoryController extends ApiController
{

    /**
     * @var subCategoryService
     */
    private $subCategoryService;
    /**
     * @param SubCategoryService $subCategoryService
     */
    public function __construct(SubCategoryService $subCategoryService)
    {
        $this->subCategoryService = $subCategoryService;
    }


    public function getSubCategoryList(Request $request)
    {
        $data = new SubCategoryResourceCollection($this->subCategoryService->getSubCategoryList($request, true));
        return $this->successResponse("Sub categories list", $data);
    }

    public function getSubCategory(SubCategory $subCategory)
    {
        $data = new SubCategoryResource($subCategory);
        return $this->successResponse("Sub category", $data);
    }


    public function saveSubCategory(Request $request)
    {
        try {
            $data = $request->all();

            if ($files = request()->file('image')) {
                $data['image'] = $this->uploadFiles($files, 'subcategory');
            } else {
                return $this->errorResponse("Please upload image", null, 610, "Error", null, 200);
            }

            if ($subCategory = $this->subCategoryService->saveSubCategory($data)) {
                return $this->successResponse("New sub category created successfully", new SubCategoryResource($subCategory));
            } else {
                return $this->errorResponse("An issue occured", null, 500, "Error", null, 500);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->exceptionResponse($th);
        }
    }
}
