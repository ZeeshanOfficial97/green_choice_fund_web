<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Eula\Web\EulaResourceCollection;
use App\Http\Resources\Portfolio\Web\PortfolioResource;
use App\Http\Resources\Portfolio\Web\PortfolioResourceCollection;
use App\Models\Category;
use App\Models\Solution;
use App\Models\User;
use App\Services\Web\PortfolioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PortfolioController extends ApiController
{

    /**
     * @var portfolioService
     */
    private $portfolioService;

    /**
     * @param portfolioService $portfolioService
     */
    public function __construct(PortfolioService $portfolioService)
    {
        $this->portfolioService = $portfolioService;
    }

    public function getPortfoliosList(Request $request)
    {
        if ($request->csv) {
            $data = PortfolioResource::collection($this->portfolioService->getPortfoliosList($request));
        } else {
            $data = new PortfolioResourceCollection($this->portfolioService->getPortfoliosList($request));
        }
        return $this->successResponse("Portfolios list", $data);
    }

    public function getPortfolioFilters(Request $request)
    {
        $data['users'] = User::where("status", "=", 1)->get(['name AS label', 'id as value'])->toArray();
        $data['categories'] = Category::where("status", "=", 1)->get(['name AS label', 'id as value'])->toArray();
        $data['solutions'] = Solution::where("status", "=", 1)->get(['name AS label', 'id as value'])->toArray();

        return $this->successResponse("Portfolio filters list", $data);
    }
}
