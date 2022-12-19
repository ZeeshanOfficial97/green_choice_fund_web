<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Solution\Web\SolutionDetailResource;
use App\Http\Resources\Solution\Web\SolutionResourceCollection;
use App\Models\Cart;
use App\Models\Solution;
use App\Models\Wishlist;
use App\Services\Web\SolutionService;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SolutionController extends ApiController
{

    /**
     * @var solutionService
     */
    private $solutionService;

    /**
     * @param solutionService $solutionService
     */
    public function __construct(SolutionService $solutionService)
    {
        $this->solutionService = $solutionService;
    }

    public function getSolutionsList(Request $request)
    {
        $data = new SolutionResourceCollection(
            $this->solutionService->getSolutionsList($request)
        );
        return $this->successResponse("Solutions list", $data);
    }

    public function getSolution(Solution $solution)
    {
        $data = ($solution);
        return $this->successResponse("Solution", new SolutionDetailResource($data));
    }


    public function saveSolution(Request $request)
    {
        // try {
        $data = $request->all();

        if ($files = $request->file('media')) {
            $data['image'] = $this->uploadFiles($files, 'solution');
        } else {
            return $this->errorResponse("Please upload image", null, 610, "Error", null, 200);
        }

        if ($solution = $this->solutionService->saveSolution($data)) {
            return $this->successResponse("New solution created successfully", ($solution));
        } else {
            return $this->errorResponse("An issue occured", null, "Error", 500, 500);
        }
        // } catch (\Throwable $th) {
        //     DB::rollback();
        //     return $this->exceptionResponse($th);
        // }
    }

    public function updateSolution(Request $request)
    {
        // try {
            $data = $request->all();

            if ($files = $request->file('media')) {
                $data['image'] = $this->uploadFiles($files, 'solution');
            }

            if ($solutionInDb = Solution::find($data['id'])) {
                if ($solution = $this->solutionService->updateSolution($solutionInDb, $data)) {
                    return $this->successResponse("Solution details updated successfully", $solution);
                } else {
                    return $this->errorResponse("An issue occured", null, 500, "Error", null, 500);
                }
            } else {
                return $this->errorResponse("Solution not found", null, 404, "Error", null, 404);
            }
        // } catch (\Throwable $th) {
        //     DB::rollback();
        //     return $this->exceptionResponse($th);
        // }
    }

    public function deleteSolution(Request $request)
    {
        try {
            $data = $request->all();

            if ($solutionInDb = Solution::find($data['id'])) {
                Wishlist::where(['solution_id' => $solutionInDb->id])->forceDelete();
                Cart::where(['solution_id' => $solutionInDb->id])->forceDelete();
                $solutionInDb->delete();
                return $this->successResponse("Solution details deleted successfully", true);
            } else {
                return $this->errorResponse("Solution not found", null, 404, "Error", null, 404);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->exceptionResponse($th);
        }
    }
}
