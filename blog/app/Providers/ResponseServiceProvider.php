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

        $response::macro('error', function ($message, $reasonCode = ReasonCodeValues::BAD_REQUEST, $data = []) {
            return response()->json([
                'success'  => false,
                'rc'      => $reasonCode,
                'message' => $message,
                'data'    => $data
            ]);
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
