<?php

namespace App\Http\Controllers\Web;

use App\Helpers\Constant;
use App\Http\Controllers\ApiController;
use App\Models\Eula;
use App\Models\Infographic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\InfoUrl;
use App\Models\InstitutionInquiry;
use App\Models\Lookup;
use App\Services\Api\V1\GeneralService as V1GeneralService;
use App\Services\Web\GeneralService;

class GeneralController extends ApiController
{

    /**
     * @var generalService
     */
    private $generalService;

    /**
     * @param GeneralService $generalService
     */
    public function __construct(V1GeneralService $generalService)
    {
        $this->generalService = $generalService;
    }

    public function donate_paypal() {
        return view('paypal.donate_paypal');
    }

    public function getEULA(Request $request)
    {
        $eula = $this->generalService->getFirstEula();
        $eula->file_url = asset('storage/' . $eula->file_url);

        return $this->successResponse("EULA", $eula);
    }

    public function saveInstitutionInquiry(Request $request)
    {

        $data = $request->all();
        $institutionInquiry = $this->generalService->saveInstitutionInquiry($data);
        return $this->successResponse("Institution inquiry submitted", $institutionInquiry);
    }

    public function saveInfographic(Request $request)
    {

        try {
            $data = $request->all();

            if ($files = request()->file('file')) {
                $data['file'] = $this->uploadFiles($files, 'infographic');
            } else {
                return $this->errorResponse("Please upload image", null, 610, "Error", null, 200);
            }

            if ($this->generalService->saveInfographic($data)) {
                $infographic = $this->generalService->getFirstInfographic();
                $data = [
                    'infographic' => asset('storage/' . $infographic->file_url)
                ];
                return $this->successResponse("New infographic added successfully", $data);
            } else {
                return $this->errorResponse("An issue occured", null, 500, "Error", null, 500);
            }
        } catch (\Throwable $th) {
            return $this->exceptionResponse($th);
        }
    }

    public function saveEULA(Request $request)
    {

        // try {
            $data = $request->all();

            if ($files = request()->file('file')) {
                $data['file'] = $this->uploadFiles($files, 'eula');
            } else {
                return $this->errorResponse("Please upload image", null, 610, "Error", null, 200);
            }

            if ($this->generalService->saveEula($data)) {
                $eula = $this->generalService->getFirstEula();
                $eula->file_url = asset('storage/' . $eula->file_url);
                return $this->successResponse("New eula added successfully", $eula);
            } else {
                return $this->errorResponse("An issue occured", null, 500, "Error", null, 500);
            }
        // } catch (\Throwable $th) {
        //     return $this->exceptionResponse($th);
        // }
    }


    public function test()
    {
        // $plaid = new Plaid(
        //     \getenv("PLAID_CLIENT_ID"),
        //     \getenv("PLAID_CLIENT_SECRET"),
        //     \getenv("PLAID_ENVIRONMENT")
        // );
        // dd($plaid);
    }
}
