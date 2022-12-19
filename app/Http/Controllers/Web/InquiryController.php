<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\ApiController;
use App\Http\Resources\AppUser\Web\AppUserResourceCollection;
use App\Http\Resources\Inquiry\Web\InquiryDetailResource;
use App\Http\Resources\Inquiry\Web\InquiryResourceCollection;
use App\Models\InstitutionInquiry;
use App\Models\User;
use App\Services\Web\AppUserService;
use App\Services\Web\InquiryService;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWT;
use Illuminate\Http\Request;


class InquiryController extends ApiController
{

    /**
     * @var inquiryService
     */
    private $inquiryService;

    /**
     * @param inquiryService $inquiryService
     */
    public function __construct(InquiryService $inquiryService)
    {
        $this->inquiryService = $inquiryService;
    }

    public function getInquiriesList(Request $request)
    {
        $data = new InquiryResourceCollection($this->inquiryService->getInquiriesList($request));
        return $this->successResponse("Inquiries list", $data);
    }

    public function getInquiry(InstitutionInquiry $institutionInquiry)
    {
        $data = new InquiryDetailResource($institutionInquiry);
        return $this->successResponse("Inquiry", $data);
    }

    public function getInquiryReasonsList(Request $request)
    {
        $data = $this->inquiryService->getInquiryReasonsList($request);
        return $this->successResponse("Inquiries list", $data);
    }
}
