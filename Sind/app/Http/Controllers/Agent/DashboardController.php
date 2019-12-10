<?php

namespace App\Http\Controllers\Agent;

use App\Http\Middleware\ManagerAuthMiddleware;
use App\Models\Order;
use App\Models\Player;
use App\Models\Profit;
use App\Models\User;
use App\Models\UserInfo;
use App\Models\Withdraw;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\Finder\SplFileInfo;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.agent:agent');
    }

    public function index(Request $request)
    {
        return view('agent.index');
    }
}
