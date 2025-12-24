<?php

namespace App\Http\Resources\Admin;

use App\Helpers\DateHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        $data['created_at'] = DateHelper::convertAndFormat($this->created_at, 'Africa/Cairo', 'Y-m-d H:i:s');
        $data['updated_at'] = DateHelper::convertAndFormat($this->updated_at, 'Africa/Cairo', 'Y-m-d H:i:s');

        return $data;
    }
}
