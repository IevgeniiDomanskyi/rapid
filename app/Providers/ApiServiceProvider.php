<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class ApiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */

    public function boot()
    {
        Response::macro('result', function ($data = [], $message = '') {
            $messages = response()->messages();
            if ( ! empty($messages)) {
                $data = null;
            }

            if (empty($messages) && ! empty($message)) {
                response()->message($message, 'success');
                $messages = response()->messages();
            }

            $response = compact('data', 'messages');
            return Response::json($response, response()->status(), []);
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