<?php

namespace App\Services\Google;

class GoogleService
{
    public function drive(): GoogleDriveService
    {
        return new GoogleDriveService();
    }
}
