<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Login as AuthLoginRequest;
use App\Http\Requests\Auth\Magic as AuthMagicRequest;
use App\Http\Requests\Auth\Register as AuthRegisterRequest;
use App\Http\Requests\Auth\Book as AuthBookRequest;
use App\Http\Requests\Auth\Recovery as AuthRecoveryRequest;
use App\Http\Requests\Auth\Profile as AuthProfileRequest;
use App\Http\Requests\Auth\Password as AuthPasswordRequest;
use App\Http\Resources\User\Me as UserMeResource;
use App\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        // 
    }

    public function me(Request $request)
    {
        $me = service('Auth')->me();
        return response()->result($me ? new UserMeResource($me) : $me);
    }

    public function login(AuthLoginRequest $request)
    {
        $input = $request->only(['email', 'password']);
        $result = service('Auth')->login($input);

        return response()->result(new UserMeResource($result), 'You were successfully logged in');
    }

    public function magic(AuthMagicRequest $request)
    {
        $input = $request->only(['token']);
        $result = service('Auth')->magic($input);

        return response()->result(new UserMeResource($result));
    }

    public function register(AuthRegisterRequest $request)
    {
        $input = $request->only(['firstname', 'lastname', 'email', 'password', 'confirm', 'link']);
        $result = service('Auth')->register($input);

        return response()->result(new UserMeResource($result), 'You were successfully registered');
    }

    public function book(AuthBookRequest $request)
    {
        $input = $request->only(['firstname', 'lastname', 'phone', 'email', 'password', 'confirm', 'gdpr', 'link']);
        $result = service('Auth')->book($input);

        return response()->result(new UserMeResource($result));
    }

    public function recovery(AuthRecoveryRequest $request)
    {
        $input = $request->only(['email']);
        $result = service('Auth')->recovery($input);

        return response()->result($result, 'Password reset has been sent');
    }

    public function activate(Request $request, Hash $hash)
    {
        $result = service('Auth')->activate($hash);

        return response()->result($result, 'You were successfully activated your account');
    }

    public function profile(AuthProfileRequest $request)
    {
        $input = $request->only(['firstname', 'lastname', 'email', 'dob', 'phone', 'exp', 'gdpr', 'link']);
        $result = service('Auth')->profile($input);

        return response()->result(new UserMeResource($result), 'Your information was saves successfully');
    }

    public function password(AuthPasswordRequest $request)
    {
        $input = $request->only(['current', 'new']);
        $result = service('Auth')->password($input);

        return response()->result(new UserMeResource($result), 'You were successfully change your password');
    }
}
