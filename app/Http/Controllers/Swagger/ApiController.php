<?php

namespace App\Http\Controllers\Swagger;

use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    public function index() {
        return view('api');
    }
    
    public function config() {
        defined('SWAGGER_HOST') or define('SWAGGER_HOST', env('API_DOMAIN'));
        defined('SWAGGER_URL') or define('SWAGGER_URL', 'http://' . SWAGGER_HOST );
        defined('SWAGGER_AUTHORIZE_URL') or define('SWAGGER_AUTHORIZE_URL', "http://" . SWAGGER_HOST . "/oauth/authorize");
        defined('SWAGGER_ACCESS_TOKEN_URL') or define('SWAGGER_ACCESS_TOKEN_URL', "http://" . SWAGGER_HOST . "/oauth/access_token");
        $swagger = \Swagger\scan(base_path('app'));
        header('Content-Type: application/json');
        echo (string)$swagger;
    }
}
