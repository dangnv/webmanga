<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class SocialController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {

        $getInfo = Socialite::driver($provider)->user();

        $data = [
            'name' => $getInfo->name,
            'email' => $getInfo->email,
            'password' => bcrypt($getInfo->id),
            'provider' => $provider,
            'provider_id' => $getInfo->id,
            'avatar' => $getInfo->avatar
        ];

        $user = User::where('email', $data['email'])
                    ->get();
        if (count($user) > 0) {
            Auth::login($user[0]);
        } else {
            $user = User::create($data);
            Auth::login($user);
        }

        return redirect()->to(route('home'));
    }
}
