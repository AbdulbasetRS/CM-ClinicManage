<?php

namespace App\Http\Resources\Admin;

use App\Helpers\DateHelper;
use App\Helpers\NotificationModelUrl;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        $data['model_url'] = NotificationModelUrl::getUrl($this->type, $this->data['model_identifier'] ?? null);
        $data['notifiable_url'] = NotificationModelUrl::getUrl($this->notifiable_type, $this->notifiable_id ?? null);
        $data['notification_url'] = route('admin.notifications.show',$this->id);

        // تحويل created_at للتوقيت المحلي
        $data['created_at'] = DateHelper::convertAndFormat($this->created_at, 'Africa/Cairo', 'Y-m-d H:i:s');

        return $data;
    }
}
