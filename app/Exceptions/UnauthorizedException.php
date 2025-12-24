<?php

namespace App\Exceptions;

use Exception;

class UnauthorizedException extends Exception
{
    protected $message;

    public function __construct($message = 'ليس لديك صلاحية للوصول إلى هذه الصفحة')
    {
        parent::__construct($message);
        $this->message = $message;
    }

    public function render($request)
    {
        // بنرجع View مخصص مع كود الحالة 403
        return response()->view('errors.admin.unauthorized', [
            'message' => $this->message
        ], 403);
    }
}
