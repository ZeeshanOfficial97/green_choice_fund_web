<?php

namespace App\Http\Resources\Eula\Web;

use App\Http\Resources\Media\Api\V1\MediaResourceCollection;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class EulaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     * @throws \Exception
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'file_url' => asset('storage/' . $this->file_url),
            'status' => $this->status ? 'active' : 'inactive',
        ];
    }
}
