<?php

namespace App\Http\Controllers;

use App\Mail\Support;
use App\Traits\MailgunTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class SupportController extends Controller
{
    use MailgunTrait;

    public function support(Request $request)
    {
        try {
            $file                        = $request->file('file');
            $subject                     = $request->get('subject');
            $messageText                 = $request->get('messageText');

            $validator = Validator::make($request->all() , [
                'file'                   => 'mimes:png,jpeg,jpg,heic,mov,mp4|max:2048',
                'messageText'            => 'required|min:10'
            ]);

            if($validator->fails()) {
                $failedRules             = $validator->failed();

                if(isset($failedRules['messageText']['Min'])) {
                    return response()->json(['status' => 'failed', 'message' => 'Message is too short!'], 422);
                }

            }

            $publicPath = public_path('/attachments/');
            $fileName = $file->getClientOriginalName();
            $fullFilePath = $publicPath . $fileName;

            $file->move($publicPath, $fileName);

            $emailData = [
                'from' => Auth::user()['email'],
                'subject' => $subject,
                'name' => Auth::user()['name'],
                'messageText' => $messageText,
                'attachment' => ['filePath' => $fullFilePath]
            ];

            Mail::to('phopixelmain@gmail.com')->send(new Support($emailData));

            return response()->json(['status' => 'success', 'message' => "Successfully Sent! We'll get back to you as soon as possible!"]);

        } catch(\Exception $e) {
            Log::error($e->getMessage());
            throw new \Exception($e->getMessage(), $e->getCode(), $e);
        }

    }
}
