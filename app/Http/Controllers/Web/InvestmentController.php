<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Investment\Web\InvestmentDetailResource;
use App\Http\Resources\Investment\Web\InvestmentResource;
use App\Http\Resources\Investment\Web\InvestmentResourceCollection;
use App\Models\UserInvestment;
use App\Services\Web\InvestmentService;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use TomorrowIdeas\Plaid\Resources\Investments;

class InvestmentController extends ApiController
{
    /**
     * @var InvestmentService
     */
    private $investmentService;

    /**
     * @param InvestmentService $investmentService
     */
    public function __construct(InvestmentService $investmentService)
    {
        $this->investmentService = $investmentService;
    }

    public function getInvestmentsList(Request $request)
    {
        // try {
        $data = $this->investmentService->getInvestmentsList($request);
        $result = new InvestmentResourceCollection($data);
        return $this->successResponse("User investments list", $result);
        // } catch (\Throwable $th) {
        //     return $this->exceptionResponse($th);
        // }
    }

    public function getInvestment(UserInvestment $userInvestment)
    {
        // try {
        if ($userInvestment) {
            $result = new InvestmentDetailResource($userInvestment);
            return $this->successResponse("User investment", $result);
        } else {
            return $this->errorResponse("Record not found", [
                'investment' => 'Record not found',
            ], 404, statusCode: 404);
        }
        // } catch (\Throwable $th) {
        //     return $this->exceptionResponse($th);
        // }
    }

    public function updateInvestmentStatus(Request $request)
    {
        try {
            if ($investmentInDb = UserInvestment::find($request->get('id'))) {
                $investmentInDb->investment_status = $request->get('investment_status');
                $investmentInDb->stripe_charge_id = $request->get('stripe_charge_id');
                $investmentInDb->save();
                return $this->successResponse("Investment details updated successfully", $investmentInDb);
            } else {
                return $this->errorResponse("Record not found", [
                    'investment' => 'Record not found',
                ], 404, statusCode: 404);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->exceptionResponse($th);
        }
    }
}
