<?php

namespace App\Services\Web;

use App\Helpers\Constant;
use App\Models\Category;
use App\Models\InstitutionInquiry;
use App\Models\Lookup;
use App\Models\Faq;
use App\Models\User;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class FaqService extends BaseService
{

    public function getFaqsList($request)
    {
        $columnArray = ['id', 'question', 'answer'];
        $sortOptions = ['desc', 'asc'];
        $derivedColArray = [];
        $derivedColKeys = [];

        $query = Faq::query();

        if ($request->q) {
            $query->where('question', 'like', '%' . $request->q . '%')->orWhere('answer', 'like', '%' . $request->q . '%');
        }

        if ($request->status != '' && $request->status != null) {
            $query->where('status', $request->status);
        }

        if ($request->sortBy && $request->dir && in_array($request->sortBy, $columnArray) && in_array($request->dir, $sortOptions)) {
            $query = $query->orderBy($request->sortBy,  $request->dir);
        } else {
            $query = $query->orderBy('id',  'desc');
        }

        $pageSize = 10;
        if ($request->length) {
            $pageSize = $request->length;
        }

        $data = $query->paginate($pageSize);
        return $data;
    }

    public function getFaqsUserList($request)
    {
        return Faq::where('status', true)->get();
    }

    public function saveFaq($data)
    {
        DB::beginTransaction();

        $faqData = array(
            'question' => $data['question'],
            'answer' => $data['answer'],
            'status' => isset($data['status']) && $data['status'] == 'true' ? true : false,
        );

        $faq = Faq::create($faqData);

        DB::commit();

        return $faq;
    }

    public function updateFaq($faqInDb, $data)
    {
        DB::beginTransaction();

        $faqInDb->question = $data['question'];
        $faqInDb->answer = $data['answer'];
        $faqInDb->status = isset($data['status']) && $data['status'] == 'true' ? true : false;

        $faqInDb->save();

        DB::commit();
        return $faqInDb;
    }
}
