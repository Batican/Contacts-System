<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController
{
    public function login(LoginRequest $request)
    {
        if(Auth::attempt(['email' => $request->email,'password' => $request->password])){
            $authUser = Auth::user();

            return $this->sendResponse(
                [
                    'user_token' => $authUser->createToken('AuthForUser')->plainTextToken,
                    'user' => $authUser
                ],
                'User login successful'
            );
        }
        else 
            return $this->sendError('Invalid credentials', ['error' => 'Unauthorized!']);
    }

    public function register(RegistrationRequest $request)
    {
        $user = User::create([...collect($request)->except(['password'])->toArray(), 'password' => bcrypt($request->password)]);
        $content = new LoginRequest();
        $content->email= $request->email;
        $content->password = $request->password;
        return $this->login($content);

        // return $this->sendResponse(
        //     [
        //         'user_token' => $user->createToken('registeredUser')->plainTextToken,
        //         'user' => $user
        //     ],'User registered successfully'
        // );
    }

    public function me(Request $request)
    {
        return $this->sendResponse($request->user(), 'User Fetched Successfully.');
    }

    public function logout(Request $request)
    {
        Auth::user()->tokens()->delete();

        return $this->sendResponse([], 'User logout Successfully.');
    }
}
