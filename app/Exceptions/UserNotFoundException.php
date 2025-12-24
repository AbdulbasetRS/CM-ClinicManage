<?php

namespace App\Exceptions;

use Exception;

class UserNotFoundException extends Exception
{
    protected $message;

    public function __construct($message = 'المستخدم غير موجود')
    {
        parent::__construct($message);
        $this->message = $message;
    }

    public function render($request)
    {
        // هنا بنرجع view مخصص مع كود 404
        return response()->view('errors.admin.user-not-found', [
            'message' => $this->message,
        ], 404);
    }
}
