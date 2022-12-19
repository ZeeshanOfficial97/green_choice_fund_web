<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\ApiController;
use App\Http\Resources\AppUser\Web\AppUserResourceCollection;
use App\Http\Resources\Category\Web\CategoryResourceCollection;
use App\Http\Resources\Inquiry\Web\InquiryDetailResource;
use App\Http\Resources\Inquiry\Web\InquiryResourceCollection;
use App\Models\Cart;
use App\Models\Category;
use App\Models\InstitutionInquiry;
use App\Models\Solution;
use App\Models\User;
use App\Models\Wishlist;
use App\Services\Web\AppUserService;
use App\Services\Web\CategoryService;
use App\Services\Web\InquiryService;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWT;
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

    public function getCategoriesList(Request $request)
    {
        $data = new CategoryResourceCollection($this->categoryService->getCategoriesList($request));
        return $this->successResponse("Categories list", $data);
    }

    public function getCategoriesDDLList(Request $request)
    {
        $data = $this->categoryService->getCategoriesDDLList($request);
        return $this->successResponse("Categories list", $data);
    }

    public function getCategory(Category $category)
    {
        $data = $category;
        return $this->successResponse("Category", $data);
    }

    public function saveCategory(Request $request)
    {
        // try {
        $data = $request->all();

        if ($file = $request->file('media')) {
            $data['image'] = $this->uploadFile($file, 'category');
            if ($file->getClientOriginalExtension() == "mp4") {
                $data['media_type'] = 'video';
            } else {
                $data['media_type'] = 'image';
            }
        } else {
            return $this->errorResponse("Please upload media file", null, 610, "Error", null, 200);
        }

        if ($category = $this->categoryService->saveCategory($data)) {
            return $this->successResponse("New category created successfully", $category);
        } else {
            return $this->errorResponse("An issue occured", null, 500, "Error", null, 500);
        }
        // } catch (\Throwable $th) {
        //     DB::rollback();
        //     return $this->exceptionResponse($th);
        // }
    }

    public function updateCategory(Request $request)
    {
        try {
            $data = $request->all();

            if ($file = $request->file('media')) {
                $data['image'] = $this->uploadFile($file, 'category');
                if ($file->getClientOriginalExtension() == "mp4") {
                    $data['media_type'] = 'video';
                } else {
                    $data['media_type'] = 'image';
                }
            }

            if ($categoryInDb = Category::find($data['id'])) {
                if ($category = $this->categoryService->updateCategory($categoryInDb, $data)) {
                    return $this->successResponse("Category details updated successfully", $category);
                } else {
                    return $this->errorResponse("An issue occured", null, 500, "Error", null, 500);
                }
            } else {
                return $this->errorResponse("Category not found", null, 404, "Error", null, 404);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->exceptionResponse($th);
        }
    }

    public function deleteCategory(Request $request)
    {
        // try {
            $data = $request->all();

            if ($categoryInDb = Category::find($data['id'])) {
                $solutionIds = Solution::where(['category_id' => $categoryInDb->id])->pluck('id')->toArray();
                Wishlist::whereIn('solution_id', $solutionIds)->forceDelete();
                Cart::whereIn('solution_id', $solutionIds)->forceDelete();
                Solution::whereIn('id', $solutionIds)->delete();
                $categoryInDb->delete();
                return $this->successResponse("Category details deleted successfully", true);
            } else {
                return $this->errorResponse("Category not found", null, 404, "Error", null, 404);
            }
        // } catch (\Throwable $th) {
        //     DB::rollback();
        //     return $this->exceptionResponse($th);
        // }
    }
}
