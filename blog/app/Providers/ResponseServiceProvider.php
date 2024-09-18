<?php

namespace App\Providers;

use App\Enums\ReasonCodeValues;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Http\ResponseFactory;

class ResponseServiceProvider extends ServiceProvider
{
    public function boot(ResponseFactory $response)
    {
        $response::macro('success', function ($data) {
            $json = [
                'success' => true,
                'data'    => $data
            ];
            return response()->json($json);
        });

        $response::macro('error', function ($data = []) {
            $json = [
                'success'  => false,
                'data'    => $data
            ];
            return response()->json($json);
        });

        $response::macro('exception', function ($message, $reasonCode = ReasonCodeValues::BAD_REQUEST, $data = []) {
            $json = [
                'success'  => false,
                'rc'      => $reasonCode,
                // 'message' => is_array($message) ? $message : ['message' => $message], // Ensure message is an array
                'data'    => is_array($message) ? $message : ['message' => $message]
            ];
            return response()->json($json);
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
