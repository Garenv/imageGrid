<?php

namespace App\Http\Controllers;

use App\Mail\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactUsController extends Controller
{
    public function submitContactUs(Request $request) {

        try {
            $validator = Validator::make($request->all() , [
                'fullName' => 'required|regex:/^[a-zA-Z\s]*$/',
                'email' => 'required|email',
                'messageText' => 'required|max:200'
            ]);

            if($validator->fails()) {

                $failedRules = $validator->failed();

                if(isset($failedRules['fullName']['Regex'])) {
                    return response()->json(['message' => 'Your name cannot contain special characters.'], 422);
                }

                if(isset($failedRules['email']['Email'])) {
                    return response()->json(['message' => "Your email is an incorrect format."], 422);
                }

                if(isset($failedRules['messageText']['Max'])) {
                    return response()->json(['message' => "Your message cannot contain more than 200 characters."], 422);
                }

            }

            $emailData = [
                'from' => $request->get('fullName'),
                'email' => $request->get('email'),
                'messageText' => $request->get('messageText')
            ];

            Mail::to('phopixelmain@gmail.com')->send(new ContactUs($emailData));

            return response()->json(['message' => "Message sent successfully!"]);

        } catch(\Exception $e) {
            Log::error($e->getMessage(), $e->getCode());
        }

    }
}
