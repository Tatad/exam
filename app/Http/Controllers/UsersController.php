<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Invite;
use Validator;
use Auth;
use App\Notifications\InviteNotification;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('registration_view');
    }

    public function registration_view($token)
    {
        $invite = Invite::where('token', $token)->first();
        return view('auth.register',['invite' => $invite]);
    }

    public function verify(Request $request){
        $input = $request->all();
        $user = User::where(['id' => Auth::user()->id, 'pin_code' => $input['pin']])->first();
        if(collect($user)->isNotEmpty()){
            $user->is_verified = 1;
            $user->save();
            return \Redirect::back()->with(['success' => 'Successfully verified your account.']);
        }else{
            return \Redirect::back()->withErrors(['msg' => 'Incorrect PIN code entered.']);
        }
    }

    public function update(Request $request){
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'user_name' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return \Redirect::back()->withErrors(['msg' => $validator->errors()]);
        }else{
            $user = User::where('id', Auth::user()->id)->first();
            $user->name = $input['name'];
            $user->user_name = $input['user_name'];
            if(collect($input['avatar'])->isNotEmpty()){
                $img = \Image::make($input['avatar'])->resize(150, 150, function($constraint)
                {
                    $constraint->aspectRatio();
                });

                $mime = $img->mime();  //edited due to updated to 2.x
                if ($mime == 'image/jpeg')
                    $extension = '.jpg';
                elseif ($mime == 'image/png')
                    $extension = '.png';
                elseif ($mime == 'image/gif')
                    $extension = '.gif';
                else
                    $extension = '';
                $name = sha1(date('YmdHis') . $this->generateRandomString());
                $img_name = $name . $extension;

                $path = public_path('/images/' . $img_name);
                $img->save($path);
                $user->avatar = '/images/'.$img_name;
            }
            $user->save();
            return \Redirect::back()->with(['success' => 'Successfully updated your account.']);
        }
    }

    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
