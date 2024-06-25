<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Propaganistas\LaravelPhone\PhoneNumber;
use Worksome\VerifyByPhone\Contracts\PhoneVerificationService;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    public function sendVerificationCode(Request $request, PhoneVerificationService $verificationService)
    {
        $parsePhone = '+213' . preg_replace('/^./', '', $request->input('phone'));

        // Send a verification code to the given number
        $verificationService->send(new PhoneNumber($parsePhone));

        return Inertia::render('VerifyCode', ['phone' => $parsePhone]);
    }
    public function verifyCode(Request $request, PhoneVerificationService $verificationService)
    {

        //Verify the verification code for the given phone number
        // $valid = $verificationService->verify(
        //     new PhoneNumber($request->input('phone')),
        //     $request->input('code')
        // );

        // if ($valid) {
        //     // Mark your user as valid
        //     dd('user_valid');
        // }

    }
    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        \Log::info($request->validated());
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();
        \Log::info($request->user());
        return Redirect::route('profile.edit');
    }
    public function show($id)
    {
        $user = User::with('socials','ratings')->find($id);
        $books = Book::with('pictures', 'tags', 'category')->where('user_id', $user->id)->orderByDesc('created_at')->take(10)->get();
        $ratings = $user->ratings;
        $result = 0;
        for ($i = 0; $i < count($ratings); $i++) {
            $result = $result + $ratings[$i]->rating;
        }
        if (count($ratings) > 0) {
            $result = $result / count($ratings);
        }
        $soldbooks = Book::with('pictures', 'tags', 'category')->where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->where('available', false);
        })->get();
        return Inertia::render('Profile/Show', [
            'user' => $user,
            'books' => $books,
            'soldbooks' => $soldbooks,
            'rating' => $result
        ]);

    }
    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    public function books($id)
    {
        $books = Book::with('user','category','tags')->where('user_id',$id)->orderBy('available')->paginate(3);
        
        return Inertia::render('Profile/Books',[
            'books'=> $books
        ]);
    }
    public function books_pagination($id)
    {
        $posts = Book::with('user','category','tags')->where('user_id',$id)->latest()->paginate(2);
        return response()->json($posts);
    }
}
