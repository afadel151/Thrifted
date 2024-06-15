<?php

namespace App\Http\Controllers;

use App\Models\Social;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function redirect(string $provier)
    {
        return Socialite::driver($provier)->redirect();
    }
    public function callback(string $provider) 
     {
        $SocialUser = Socialite::driver( $provider)->user();
        $user = User::where('email',$SocialUser->getEmail())->first();
        // dd($user);
        $name = $SocialUser->getNickname() ?? $SocialUser->getName();
       
        if (!$user) {
            $user = User::create([
                'name' => $name,
                'email' => $SocialUser->getEmail(),
                'password' => Hash::make(Str::random(7))
            ]);
            $user->socials()->create([
                'provider' => $provider,
                'provider_id' => $SocialUser->getId(),
                'provider_token' => $SocialUser->token,
                'provider_refresh_token' => $SocialUser->refreshToken,
                'provider_avatar' => $SocialUser->getAvatar(),
            ]);
            // dd($user);
            auth()->login($user);
            return redirect()->route('dashboard');
        }
        $socials = Social::where('provider',$provider)
        ->where('user_id',$user->id)
        ->first();
        if (!$socials) {
            $user->socials()->create([
                'provider' => $provider,
                'provider_id' => $SocialUser->getId(),
                'provider_token' => $SocialUser->token,
                'provider_refresh_token' => $SocialUser->refreshToken,
                'provider_avatar' => $SocialUser->getAvatar(),
            ]);
        }   
        auth()->login($user);
        return redirect()->route('dashboard');
    }
}
