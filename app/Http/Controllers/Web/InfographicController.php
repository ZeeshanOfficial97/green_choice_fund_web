<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Infographic\Web\InfographicResourceCollection;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Solution;
use App\Models\Wishlist;
use App\Services\Web\InfographicService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InfographicController extends ApiController
{

    /**
     * @var infographicService
     */
    private $infographicService;

    /**
     * @param infographicService $infographicService
     */
    public function __construct(InfographicService $infographicService)
    {
        $this->infographicService = $infographicService;
    }

    public function getInfographicsList(Request $request)
    {
        $data = new InfographicResourceCollection($this->infographicService->getInfographicsList($request));
        return $this->successResponse("Infographics list", $data);
    }

    public function saveInfographic(Request $request)
    {
        // try {
        $data = $request->all();

        if ($file = $request->file('media')) {
            $data['file'] = $this->uploadFile($file, 'infographic');
        } else {
            return $this->errorResponse("Please upload media file", null, 610, "Error", null, 200);
        }

        if ($infographic = $this->infographicService->saveInfographic($data)) {
            return $this->successResponse("New infographic created successfully", $infographic);
        } else {
            return $this->errorResponse("An issue occured", null, 500, "Error", null, 500);
        }
        // } catch (\Throwable $th) {
        //     DB::rollback();
        //     return $this->exceptionResponse($th);
        // }
    }

}
