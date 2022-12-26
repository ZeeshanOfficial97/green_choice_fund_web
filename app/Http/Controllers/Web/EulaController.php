<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Eula\Web\EulaResourceCollection;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Solution;
use App\Models\Wishlist;
use App\Services\Web\EulaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EulaController extends ApiController
{

    /**
     * @var eulaService
     */
    private $eulaService;

    /**
     * @param eulaService $eulaService
     */
    public function __construct(EulaService $eulaService)
    {
        $this->eulaService = $eulaService;
    }

    public function getEulasList(Request $request)
    {
        $data = new EulaResourceCollection($this->eulaService->getEulasList($request));
        return $this->successResponse("Eulas list", $data);
    }

    public function saveEula(Request $request)
    {
        // try {
        $data = $request->all();

        if ($file = $request->file('media')) {
            $data['file'] = $this->uploadFile($file, 'Eula');
        } else {
            return $this->errorResponse("Please upload media file", null, 610, "Error", null, 200);
        }

        if ($eula = $this->eulaService->saveEula($data)) {
            return $this->successResponse("New eula created successfully", $eula);
        } else {
            return $this->errorResponse("An issue occured", null, 500, "Error", null, 500);
        }
        // } catch (\Throwable $th) {
        //     DB::rollback();
        //     return $this->exceptionResponse($th);
        // }
    }

}
