<?php

namespace App\Http\Resources\Admin;

use App\Helpers\DateHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        $data['date'] = DateHelper::convertAndFormat($this->date, 'UTC', 'Y-m-d');
        $data['start_time'] = DateHelper::convertAndFormat($this->start_time, 'UTC', 'g:i a');
        $data['end_time'] = DateHelper::convertAndFormat($this->end_time, 'UTC', 'g:i a');
        $data['created_at'] = DateHelper::convertAndFormat($this->created_at, 'Africa/Cairo', 'Y-m-d g:i a');
        $data['updated_at'] = DateHelper::convertAndFormat($this->updated_at, 'Africa/Cairo', 'Y-m-d g:i a');

        return $data;
    }
}
