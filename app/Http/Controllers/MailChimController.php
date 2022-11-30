<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\Newsletter\NewsletterFacade as Newsletter;

class MailChimController extends Controller
{
    /**
     * @param Request $request
     * @throws ValidationException
     */
    public function subscribe(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);

        if (!Newsletter::isSubscribed($request->email)) {
           Newsletter::subscribePending($request->email);
        }
    }
}
