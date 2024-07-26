<?php

namespace App\Http\Controllers\MailVerification;

use App\Http\Controllers\Api\AdminUser\Model\Admin;
use App\Http\Controllers\Controller as BaseController;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class VerifyEmailController extends BaseController
{
    public function __invoke(Request $request, Admin $admin)
    {
        if ($admin->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified'], 200);
        }

        $admin->markEmailAsVerified();

        return response()->json(['message' => 'Email verified successfully'], 200);
    }
}
