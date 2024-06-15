<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function redirect(string $provier)
    {
        return Socialite::driver( $provier)->redirect();
    }
    public function callback(string $provider) 
     {
        $user = Socialite::driver( $provider)->user();
        dd($user);
    }
}
