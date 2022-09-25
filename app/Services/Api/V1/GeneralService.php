<?php

namespace App\Services\Api\V1;

use App\Helpers\Constant;
use App\Models\Cart;
use App\Models\CategoriesMedia;
use App\Models\Category;
use App\Models\Eula;
use App\Models\Infographic;
use App\Models\InfoUrl;
use App\Models\InstitutionInquiry;
use App\Models\Lookup;
use App\Models\Solution;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GeneralService extends BaseService
{
    public function getUserTypes($request = null)
    {
        return Lookup::where(['group_code' => Constant::LOOKUP_GROUP_CODE['userType'], 'status' => true])->get();
    }
    public function getContactUsReasons($request = null)
    {
        return Lookup::where(['group_code' => Constant::LOOKUP_GROUP_CODE['contactReason'], 'status' => true])->get();
    }
    public function getInfoUrls($request = null)
    {
        return InfoUrl::where(['status' => true])->get();
    }

    public function getFirstInfographic($request = null)
    {
        return Infographic::first();
    }

    public function getFirstEULA($request = null)
    {
        return Eula::first();
    }

    public function saveInstitutionInquiry($data)
    {

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
        return $institutionInquiry;
    }

    public function saveInfographic($data)
    {

        if (isset($data['file'])) {
            foreach ($data['file'] as $file) {
                $mediaData[] = array(
                    'file_url' => $file['url'],
                    'name' => 'Infographic',
                    'description' => 'Infographic'
                );
            }

            if (isset($fileData)) {
                Infographic::query()->delete();
                Infographic::insert($fileData);
            }
        }

        return true;
    }

    public function saveEula($data)
    {

        if (isset($data['file'])) {
            foreach ($data['file'] as $file) {
                $fileData[] = array(
                    'file_url' => $file['url'],
                    'name' => 'Terms and conditions',
                    'description' => 'Terms and conditions for investments'
                );
            }

            if (isset($fileData)) {
                Eula::query()->delete();
                Eula::insert($fileData);
            }
        }

        return true;
    }
}
