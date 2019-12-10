<?php

namespace App\Http\Controllers\Manager;

use App\Http\Middleware\ManagerAuthMiddleware;
use App\Models\Agent;
use App\Models\AgentInfo;
use App\Models\IncomeDailySettle;
use App\Models\Order;
use App\Models\Player;
use App\Models\Profit;
use App\Models\Relation;
use App\Models\TradeRecord;
use App\Models\UserInfo;
use App\Models\Withdraw;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Finder\SplFileInfo;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.manager:manager');

    }

    public function index(Request $request)
    {
        return view('manager.index');
    }

}
