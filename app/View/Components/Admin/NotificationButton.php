<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Config; // يجب استدعاء الواجهة Facade

class NotificationButton extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string|null
    {
        if (Config::get('services.notifications_enabled')) {
            return view('components.admin.notification-button');
        }
        return null;
    }
}
