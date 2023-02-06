<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;

class MessageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */

    public function boot()
    {
        Response::macro('message', function ($key, $type = 'error', $code = 200) {
            if (empty($this->messages)) {
                $this->messages = [];
            }

            $replace = [];
            if (is_array($key)) {
                $replace = $key[1];
                $key = $key[0];
            }

            $this->messages[] = [
                'text' => __($key, $replace),
                'type' => config('constants.message.'.$type, $type),
            ];

            $this->code = $code;
        });

        Response::macro('messages', function () {
            if (empty($this->messages)) {
                $this->messages = [];
            }

            return $this->messages;
        });

        Response::macro('status', function () {
            if (empty($this->code)) {
                $this->code = 200;
            }

            return $this->code;
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}