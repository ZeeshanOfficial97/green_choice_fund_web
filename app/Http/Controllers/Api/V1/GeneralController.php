<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\Constant;
use App\Http\Controllers\ApiController;
use App\Models\Eula;
use App\Models\Infographic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\InfoUrl;
use App\Models\InstitutionInquiry;
use App\Models\Lookup;
use App\Services\Api\V1\GeneralService;

class GeneralController extends ApiController
{

    /**
     * @var generalService
     */
    private $generalService;

    /**
     * @param GeneralService $generalService
     */
    public function __construct(GeneralService $generalService)
    {
        $this->generalService = $generalService;
    }

    public function getSplashMetadata(Request $request)
    {
        $userType = $this->generalService->getUserTypes();
        $contactUsReason = $this->generalService->getContactUsReasons();
        $infoUrl = $this->generalService->getInfoUrls();
        $infographic = $this->generalService->getFirstInfographic();

        $data = [
            'userType' => $userType,
            'contactUsReason' => $contactUsReason,
            'infoUrl' => $infoUrl,
            'infographic' => asset('storage/' . $infographic->file_url)
        ];

        return $this->successResponse("Splash Metadata", $data);
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

        $inquiryData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'country_code' => $data['country_code'],
            'contact_no' => $data['contact_no'],
            'address' => $data['address'],
            'company_url' => isset($data['company_url']) ? $data['company_url'] : null,
            'description' => isset($data['description']) ? $data['description'] : null,
            'contact_reason_id' => $data['contact_reason_id'],
            'user_id' => Auth::id()
        ];

        $institutionInquiry = InstitutionInquiry::create($inquiryData);

        return $this->successResponse("Splash Metadata", $institutionInquiry);
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
