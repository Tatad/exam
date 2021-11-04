<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Invite;
use Validator;
use Auth;
use App\Notifications\InviteNotification;

class InviteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function process_invites(Request $request)
    {
        if($request->user()->user_role != 'admin'){
            return response()->json('user must have a role of admin to invite', 422);
        }
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email'
        ]);
        $validator->after(function ($validator) use ($request) {
            if (Invite::where('email', $request->input('email'))->exists()) {
                $validator->errors()->add('email', 'There exists an invite with this email!');
            }
        });
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        do {
            $token = \Str::random(20);
        } while (Invite::where('token', $token)->first());
        Invite::create([
            'token' => $token,
            'email' => $request->input('email')
        ]);
        $url = \URL::temporarySignedRoute(
     
            'registration', now()->addMinutes(300), ['token' => $token]
        );

        \Notification::route('mail', $request->input('email'))->notify(new InviteNotification($url));
        return response()->json('Email invitation sent successfully.', 200);
    }
}
