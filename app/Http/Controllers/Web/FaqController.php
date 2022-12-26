<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Faq\Web\FaqResource;
use App\Http\Resources\Faq\Web\FaqResourceCollection;
use App\Models\Faq;
use App\Services\Web\FaqService;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FaqController extends ApiController
{

    /**
     * @var faqService
     */
    private $faqService;

    /**
     * @param faqService $faqService
     */
    public function __construct(FaqService $faqService)
    {
        $this->faqService = $faqService;
    }

    public function getFaqsUserList(Request $request)
    {
        $data = FaqResource::collection(
            $this->faqService->getFaqsUserList($request)
        );
        return $this->successResponse("Faqs list", $data);
    }


    public function getFaqsList(Request $request)
    {
        $data = new FaqResourceCollection(
            $this->faqService->getFaqsList($request)
        );
        return $this->successResponse("Faqs list", $data);
    }

    public function getFaq(Faq $faq)
    {
        $data = ($faq);
        return $this->successResponse("Faq", new FaqResource($data));
    }


    public function saveFaq(Request $request)
    {
        // try {
        $data = $request->all();

        if ($faq = $this->faqService->saveFaq($data)) {
            return $this->successResponse("New faq created successfully", ($faq));
        } else {
            return $this->errorResponse("An issue occured", null, "Error", 500, 500);
        }
        // } catch (\Throwable $th) {
        //     DB::rollback();
        //     return $this->exceptionResponse($th);
        // }
    }

    public function updateFaq(Request $request)
    {
        // try {
        $data = $request->all();

        if ($files = $request->file('media')) {
            $data['image'] = $this->uploadFiles($files, 'faq');
        }

        if ($faqInDb = Faq::find($data['id'])) {
            if ($faq = $this->faqService->updateFaq($faqInDb, $data)) {
                return $this->successResponse("Faq details updated successfully", $faq);
            } else {
                return $this->errorResponse("An issue occured", null, 500, "Error", null, 500);
            }
        } else {
            return $this->errorResponse("Faq not found", null, 404, "Error", null, 404);
        }
        // } catch (\Throwable $th) {
        //     DB::rollback();
        //     return $this->exceptionResponse($th);
        // }
    }

    public function deleteFaq(Request $request)
    {
        try {
            $data = $request->all();

            if ($faqInDb = Faq::find($data['id'])) {
                $faqInDb->delete();
                return $this->successResponse("Faq details deleted successfully", true);
            } else {
                return $this->errorResponse("Faq not found", null, 404, "Error", null, 404);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->exceptionResponse($th);
        }
    }
}
