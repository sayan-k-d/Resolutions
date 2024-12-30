<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Resolution;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        return view('Dashboard.layout.login');
    }
    public function signup()
    {
        return view('Dashboard.layout.register');
    }

    public function signin(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:6',
            ]);

            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return redirect()->route('dashboard');
            }
            return redirect()->back()->with('error', 'The provided credentials do not match our records.')->withInput($request->only('email', 'remember'));
        } catch (\Exception $e) {
            //throw $th;
            return redirect()->back()->with('error', $e->getMessage());
        }

    }
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|min:3',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:6',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
            Auth::login($user);
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            //throw $th;
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function logout()
    {
        try {
            Auth::logout();
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            //throw $th;
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function profile()
    {
        try {
            $user = Auth::user();
            $resolutions = Resolution::where('user_id', $user->id)->get();
            return view('Dashboard.layout.profile', compact('user', 'resolutions'));
        } catch (\Exception $e) {
            //throw $th;
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function edit()
    {
        try {
            $user = Auth::user();
            $resolutions = Resolution::where('user_id', $user->id)->get();
            $editProfile = true;
            return view('Dashboard.layout.profile', compact('user', 'resolutions', 'editProfile'));
        } catch (\Exception $e) {
            //throw $th;
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|min:3',
                'email' => 'required|email',
            ]);

            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();

            return redirect()->route('profile');
        } catch (\Exception $e) {
            //throw $th;
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function deleteProfile($id)
    {
        try {
            $user = User::find($id);

            if ($user && auth()->id() === $user->id) {
                $comments = Comment::where('user_id', $user->id)->pluck('resolution_id')->toArray();
                $likes = Like::where('user_id', $user->id)->pluck('resolution_id')->toArray();
                $resolutions = Resolution::whereIn('id', $comments)->get();
                foreach ($resolutions as $key => $resolution) {
                    $totalComments = $resolution->comments;
                    $resolution->comments = $totalComments - 1;
                    $resolution->save();
                }
                $resolutions = Resolution::whereIn('id', $likes)->get();
                // dd($resolutions);
                foreach ($resolutions as $key => $resolution) {
                    $totalLikes = $resolution->likes;
                    $resolution->likes = $totalLikes - 1;
                    $resolution->save();
                }
                $user->delete();
                return redirect()->route('signup');
            }
            return redirect()->back()->with(['error' => 'You are not authorized to delete this User.']);
        } catch (\Exception $e) {
            //throw $th;
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function changePassword()
    {
        try {
            $user = Auth::user();
            $resolutions = Resolution::where('user_id', $user->id)->get();
            $editPassword = true;
            return view('Dashboard.layout.profile', compact('user', 'resolutions', 'editPassword'));
        } catch (\Exception $e) {
            //throw $th;
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function updatePassword(Request $request, $id)
    {
        try {
            $request->validate([
                'oldPassword' => 'required|string|min:6',
                'password' => 'required|string|min:6',
            ]);

            $user = User::find($id);
            if (Auth::check() && Hash::check($request->oldPassword, $user->password)) {
                $user->password = bcrypt($request->password);
                $user->save();
                return redirect()->route('profile');
            }
            return redirect()->back()->with(['error' => 'The provided old password is incorrect.']);
        } catch (\Exception $e) {
            //throw $th;
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
