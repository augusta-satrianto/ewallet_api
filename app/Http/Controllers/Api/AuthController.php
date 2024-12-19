<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ApiResponses;

    // Check if email is already registered
    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $emailExists = User::where('email', $request->email)->exists();

        return response([
            'is_email_exist' =>   $emailExists

        ], 200);
    }

    //Register User
    public function register(Request $request)
    {
        try {
            $attrs = $request->validate([
                'name' => 'required|string',
                'email' => 'required|string|unique:users',
                'password' => 'required|min:8',
                'pin' => 'required|min:6',
            ]);

            $image = $this->saveImage($request->profile_picture, 'profile');

            $user = User::create([
                'name' => $attrs['name'],
                'email' => $attrs['email'],
                'password' => bcrypt($attrs['password']),
                'card_number' => implode('', array_map(fn() => random_int(0, 9), range(1, 16))),
                'pin' => $attrs['pin'],
                'profile_picture' =>   $image
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            // Return User
            $userData = $user->toArray();
            $userData['token'] = $token;

            return response(
                $userData
            );
        } catch (ValidationException $validationException) {
            return $this->sendValidationErrorResponse($validationException);
        } catch (\Exception $e) {
            // Tangani kesalahan umum
            return $this->sendErrorResponse('Registration failed. Please try again.', 500);
        }
    }


    //Login user
    public function login(Request $request)
    {
        // Validasi input
        $attrs = $request->validate([
            'email' => 'required|email',  // Validasi email
            'password' => 'required'
        ]);

        // Cek kredensial pengguna
        if (!Auth::attempt($attrs)) {
            return response([
                'message' => 'Invalid credentials.'
            ], 401);
        }

        $user = $request->user();

        $token = $user->createToken('secret')->plainTextToken;

        $userData = $user->toArray();
        $userData['token'] = $token;

        return response(
            $userData
        );
    }

    //get user details
    public function user()
    {
        return response([
            'user' => auth()->user()
        ], 200);
    }

    // Get all users except the currently logged-in user
    public function alluser()
    {
        $loggedInUserId = auth()->id(); // Get the currently logged-in user's ID

        return response(
            User::where('id', '!=', $loggedInUserId)
                ->orderBy('id', 'asc')
                ->get()
        );
    }


    //Logout User
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response([
            'message' => 'Logout Success.'
        ], 200);
    }
}
