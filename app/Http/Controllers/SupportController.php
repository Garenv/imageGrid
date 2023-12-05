<?php

namespace App\Http\Controllers;

use App\Mail\Support;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class SupportController extends Controller
{
    public function support(Request $request)
    {
        try {
            $subject                     = $request->get('subject');
            $messageText                 = $request->get('messageText');

            $validator = Validator::make($request->all() , [
                'file'                   => 'nullable|mimes:png,jpeg,jpg,heic,mov,mp4|max:2048',
                'messageText'            => 'required|min:10'
            ]);

            if($validator->fails()) {
                $failedRules             = $validator->failed();

                if(isset($failedRules['messageText']['Min'])) {
                    return response()->json(['status' => 'failed', 'message' => 'Message is too short!'], 422);
                }

                return response()->json(['errors' => $validator->errors()], 422);

            }

            $emailData = [
                'from' => Auth::user()['email'],
                'name' => Auth::user()['name'],
                'UserID' => Auth::user()['UserID'],
                'subject' => $subject,
                'messageText' => $messageText
            ];

            if($request->hasFile('file')) {
                $file = $request->file('file');
                $publicPath = public_path('/attachments/');
                $fileName = $file->getClientOriginalName();
                $fullFilePath = $publicPath . $fileName;
                $emailData['attachment'] = ['filePath' => $fullFilePath];
                $file->move($publicPath, $fileName);
            }

            try {
                Mail::to('support@phopixel.com')->send(new Support($emailData));
            } catch (\Exception $e) {
                Log::error($e->getMessage());
            }

            return response()->json(['status' => 'success', 'message' => "Successfully Sent! We'll get back to you as soon as possible!"]);

        } catch(\Exception $e) {
            Log::error($e->getMessage());
        }

    }
}
