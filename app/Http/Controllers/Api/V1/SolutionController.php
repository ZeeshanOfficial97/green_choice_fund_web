<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;

use App\Http\Resources\Solution\Api\V1\SolutionResource;
use App\Http\Resources\Solution\Api\V1\SolutionResourceCollection;

use App\Models\Solution;
use App\Services\Api\V1\SolutionService;
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


    public function getSolutionList(Request $request)
    {
        $data = new SolutionResourceCollection($this->solutionService->getSolutionList($request, true));
        return $this->successResponse("Solution list", $data);
    }

    public function getSolution(Solution $solution)
    {
        $data = new SolutionResource($solution);
        return $this->successResponse("Solution", $data);
    }

    public function saveSolution(Request $request)
    {
        try {
            $data = $request->all();

            if ($files = request()->file('image')) {
                $data['image'] = $this->uploadFiles($files, 'solution');
            } else {
                return $this->errorResponse("Please upload image", null, 610, "Error", null, 200);
            }

            if ($solution = $this->solutionService->saveSolution($data)) {
                return $this->successResponse("New solution created successfully", new SolutionResource($solution));
            } else {
                return $this->errorResponse("An issue occured", null, "Error", 500, 500);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->exceptionResponse($th);
        }
    }

}
