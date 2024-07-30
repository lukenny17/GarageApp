<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\RedirectHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        return $request->user()->hasVerifiedEmail()
                    ? RedirectHelper::redirectToDashboard()
                    : view('auth.verify-email');
    }
}
