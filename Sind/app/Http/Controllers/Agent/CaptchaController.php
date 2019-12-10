<?php

namespace App\Http\Controllers\Agent;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class CaptchaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function captcha($tmp){
        return ['src'=>captcha_src("flat")];
    }
}
