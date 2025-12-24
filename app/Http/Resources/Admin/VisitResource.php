<?php

namespace App\Http\Resources\Admin;

use App\Helpers\DateHelper;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class VisitResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        $data['created_at'] = DateHelper::convertAndFormat($this->created_at, 'Africa/Cairo', 'Y-m-d g:i a');
        $data['updated_at'] = DateHelper::convertAndFormat($this->updated_at, 'Africa/Cairo', 'Y-m-d g:i a');
       
        return $data;
    }
}
