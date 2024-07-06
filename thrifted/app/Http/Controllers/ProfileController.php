<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Book;
use App\Models\Category;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
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
    public function rate(Request $request)
    {
        try {
            if ($request->input('comment') != '') {
                $rating = Rating::create([
                    'user_id' => $request->input('user_id'),
                    'rated_user_id' => $request->input('rated_user_id'),
                    'rating' => $request->input('rating'),
                    'comment' => $request->input('comment')
                ]);
            }else {
                $rating = Rating::create([
                    'user_id' => $request->input('user_id'),
                    'rated_user_id' => $request->input('rated_user_id'),
                    'rating' => $request->input('rating'),
                ]);
            }
            $rating->load('user');
            return response()->json($rating);
        } catch (\Throwable $th) {
            throw $th;
        }

    }
    /**
     * Update the user's profile information.
     */
    public function update_picture(Request $request)
    {
        \Log::info('user_id :'.$request->input('user_id'));
        $user = User::find($request->input('user_id'));
        if ($request->hasFile('file')) {
            $request_file = $request->file('file');
            $path = '/public/users/';
            $NewPath = Storage::disk('local')->putFile($path, $request_file);
            $user->update([
                'picture' => $NewPath
            ]);
            return response()->json($NewPath);
        }else {
            return response()->json('failure');
        }
    }
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
        $ratings->load('user');
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
            'rating' => $result,
            'ratings' => $ratings
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
        
        return Inertia::render('Profile/Books',[
            'user_id' => $id,
        ]);
       
    }

    public function books_pagination($id)
    {
        $books = Book::with('user','category','tags')->where('user_id',$id)->orderBy('available')->paginate(12);
        return response()->json($books);
    }
    // public function rate(Request $rquest )
    // {
    //     $rater_id = Auth::user()->id;
    //     $rated_id = $rquest->input('rated_id');
    //     $rating = $rquest->input('rating');
    //     dd($rating);
    // }

}
