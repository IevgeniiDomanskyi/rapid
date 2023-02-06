<?php

namespace App\Http\Services;

use App\Http\Services\Service;
use App\Events\Auth\Register as AuthRegisterEvent;
use App\Events\Auth\Recovery as AuthRecoveryEvent;
use App\Events\Auth\ChangeEmail as AuthChangeEmailEvent;
use App\Events\Auth\ChangePassword as AuthChangePasswordEvent;
use App\Hash;
use App\User;

class AuthService extends Service
{
    public function __construct()
    {
        // 
    }

    public function me()
    {
        return auth()->user();
    }

    public function login(array $input)
    {
        if (auth()->attempt($input)) {
            $me = auth()->user();
            
            $me->token = $me->createToken($me->email)->plainTextToken;
            return $me;
        }

        return response()->message('Your login or password is incorect', 'error', 422);
    }

    public function magic(array $input)
    {
        $me = service('User')->getByAuthToken($input['token']);
        if ($me) {
            service('User')->update($me, [
                'auth_token' => '',
            ]);
            $me->token = $me->createToken($me->email)->plainTextToken;
            return $me;
        }

        return response()->message('Link is wrong or expired', 'error', 422);
    }
    
    public function register(array $input)
    {
        if ($input['confirm']) {
            $input['password'] = bcrypt($input['password']);
            $input['is_active'] = false;
            $input['is_notified'] = true;
            $input['role'] = 0;

            $user = service('User')->create($input);
            $user->token = $user->createToken($user->email)->plainTextToken;

            if ($user->gdpr) {
                event(new AuthRegisterEvent($user, $input['link']));
            }

            return $user;
        }

        return response()->message('Your should agree the terms and policy', 'error', 422);
    }

    public function registerTemp(array $input)
    {
        $input['password'] = bcrypt(empty($input['password']) ? substr(md5($input['email']), 0, 8) : $input['password']);
        $input['is_active'] = false;
        $input['is_notified'] = true;
        $input['role'] = 0;

        $user = service('User')->create($input);
        $user->token = $user->createToken($user->email)->plainTextToken;

        return $user;
    }

    public function book(array $input)
    {
        $auth = [
            'email' => $input['email'],
            'password' => $input['password'],
        ];

        $user = service('User')->getByEmail($input['email']);
        if ( ! empty($user)) {
            if (auth()->attempt($auth)) {
                $me = auth()->user();

                $me = service('User')->update($me, [
                    'gdpr' => $input['gdpr'],
                ]);
                
                $me->token = $me->createToken($me->email)->plainTextToken;
                return $me;
            }

            return response()->message('Your password is incorect', 'error', 422);
        }

        return $this->register($input);
    }
    
    public function recovery(array $input)
    {
        $user = service('User')->getByEmail($input['email']);
        
        if ( ! empty($user)) {
            if ($user->gdpr) {
                $password = crypt($user->password, time());
                $data['password'] = bcrypt($password);

                $user = service('User')->update($user, $data);

                event(new AuthRecoveryEvent($user, $password));
            }

            return true;
        }

        return response()->message('Email is incorect', 'error', 422);
    }

    public function activate(Hash $hash)
    {
        $user = service('Hash')->getByHash($hash, Hash::TYPE_VERIFY, (new User));

        if ($user) {
            $user = service('User')->update($user, [
                'is_active' => true,
            ]);
            service('Hash')->remove($user, Hash::TYPE_VERIFY);

            return true;
        }

        return false;
    }

    public function profile(array $input)
    {
        $user = auth()->user();

        $old_email = false;
        if ($user->email != $input['email']) {
            $old_email = $user->email;
            $input['is_active'] = false;
        }

        $user = service('User')->update($user, $input);

        $exp = service('Exp')->getByValue($input['exp']);
        $user->exp()->associate($exp);
        $user->save();

        // service('User')->customersPostcodeSave($user, $input['postcode']);

        service('Card')->customerUpdate($user);

        if ( ! empty($old_email)) {
            if ($user->gdpr) {
                event(new AuthChangeEmailEvent($user, $old_email, $input['link']));
            }
        }

        return $user;
    }

    public function password(array $input)
    {
        $user = auth()->user();
        if (app('hash')->check($input['current'], $user->password)) {
            $input['password'] = bcrypt($input['new']);

            $user = service('User')->update($user, $input);
            if ($user->gdpr) {
                event(new AuthChangePasswordEvent($user, $input['new']));
            }

            return $user;
        }

        return response()->message('You entered wrong current password', 'error', 422);
    }
}
