<?php

namespace App\Http\Controllers\Agent;
use App\Models\Agent;
use App\Models\AgentInfo;
use App\Models\OrderBackup;
use App\Models\Relation;
use App\Models\UserDailySettle;
use App\Models\Payoff;
use App\Models\Order;
use App\Models\Profit;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BuyUser;
use App\Models\IncomeDailySettle;
use App\Models\AgentDailySettle;
use App\Models\TradeRecord;
use App\Models\UserInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;


class statManagerController  extends Controller
{
    public function statUser(Request $request) {
        if ($request->input('history')) {
            $date_begin = $request->input('date_begin') ? Carbon::createFromTimestamp(strtotime($request->input('date_begin'))) : Carbon::now()->subDays(1);
            $date_end = $request->input('date_end') ? Carbon::createFromTimestamp(strtotime($request->input('date_end'))) : Carbon::now()->subDays(1);
            $user_daily_list = Payoff::query()->with('info')
                ->select(['payoff.user_id', DB::raw('sum(bet_num) as bet_num'),
                    DB::raw('sum(bet_money) as bet_money'),
                    DB::raw('sum(result) as result'), 'date'])
                ->leftJoin('user', 'user.user_id', '=', 'payoff.user_id')
                ->where('user.role_id', 1)
                ->whereDate('date', ">=", $date_begin->toDateString())
                ->whereDate('date', '<=', $date_end->toDateString())
                ->groupBy('payoff.user_id');
            if ($request->input('user_id')) {
                $user_daily_list = $user_daily_list->where('payoff.user_id', $request->input('user_id'));
            }
            $all_income = Payoff::query()
                ->select([DB::raw('sum(result) as all_income'), DB::raw('sum(bet_money) as bet_money'), DB::raw('sum(bet_num) as bet_num')])
                ->whereDate('date', ">=", $date_begin->toDateString())
                ->whereDate('date', '<=', $date_end->toDateString());
            $children_users = UserInfo::query()->where('parent_user_id', auth('agent')->user()->user_id)->get(['user_id'])->toArray();
            $user_daily_list = $user_daily_list->whereIn('payoff.user_id', $children_users);
            $all_income = $all_income->whereIn('user_id', $children_users);
            switch ($request->input('column')) {
                case 'bet_num':
                    $order = 'bet_num';
                    break;
                case 'bet_money':
                    $order = 'bet_money';
                    break;
                case 'result':
                    $order = 'result';
                    break;
                default:
                    $order = 'payoff.user_id';
                    break;
            }
            $user_daily_list = $user_daily_list->orderBy($order, $request->input('sort') ? $request->input('sort') : 'desc')->paginate(10);
            $all_income = $all_income->get()->toArray();
        } else {
            $user_daily_list = Order::query()->with('info')
                ->select(['user_id', DB::raw('count(*) as bet_num'),
                    DB::raw('sum(bet_money) as bet_money'),
                    DB::raw('sum(result) as result'),
                    DB::raw('date(order_dateTime) as date')])
                ->groupBy('user_id');
            if ($request->input('user_id')) {
                $user_daily_list = $user_daily_list->where('user_id', $request->input('user_id'));
            }
            $all_income = Order::query()
                ->select(['user_id', DB::raw('count(*) as bet_num'),
                    DB::raw('sum(bet_money) as bet_money'),
                    DB::raw('sum(result) as all_income')]);
            $children_users = UserInfo::query()->where('parent_user_id', auth('agent')->user()->user_id)->get(['user_id'])->toArray();
            $user_daily_list = $user_daily_list->whereIn('user_id', $children_users);
            $all_income = $all_income->whereIn('user_id', $children_users);
            switch ($request->input('column')) {
                case 'bet_num':
                    $order = 'bet_num';
                    break;
                case 'bet_money':
                    $order = 'bet_money';
                    break;
                case 'result':
                    $order = 'result';
                    break;
                default:
                    $order = 'user_id';
                    break;
            }
            $user_daily_list = $user_daily_list->orderBy($order, $request->input('sort') ? $request->input('sort') : 'desc')->paginate(10);
            $all_income = $all_income->get()->toArray();
            $date_begin = date('Y-m-d');
            $date_end = date('Y-m-d');
        }
        return view('agent.statManager.statUser',[
            'user_daily_list'=> $user_daily_list,
            'date_begin' => $date_begin,
            'date_end' => $date_end,
            'all_income' => $all_income
        ]);
    }
    public function statUser_detail(Request $request) {
        $date_begin = $request->input('date_begin');
        $date_end = $request->input('date_end');
        $user_id = $request->input("user_id");
        $children_users = UserInfo::query()->where('parent_user_id', auth('agent')->user()->user_id)->get(['user_id'])->toArray();

        $day_money_list = Payoff::query()->with('info')
            ->where('user_id',$user_id)
            ->whereIn('user_id', $children_users)
            ->whereDate('date','>=',$date_begin)
            ->whereDate('date','<=',$date_end);
        switch ($request->input('column')) {
            case 'bet_num':
                $order = 'bet_num';
                break;
            case 'bet_money':
                $order = 'bet_money';
                break;
            case 'result':
                $order = 'result';
                break;
            default:
                $order = 'payoff.user_id';
                break;
        }
        $day_money_list = $day_money_list->orderBy($order, $request->input('sort')?$request->input('sort'):'desc')->paginate(10);

        return view('agent.statManager.statUser_detail',[
            'day_money_list'=> $day_money_list,
            'user_id' => $user_id,
            'date_begin' => $date_begin,
            'date_end' => $date_end
        ]);
    }

    public function day_income_stat(Request $request) {
        $time = $request->input('time');
        $user_id = $request->input('user_id');
        $children_users = UserInfo::query()->where('parent_user_id', auth('agent')->user()->user_id)->get(['user_id'])->toArray();

        $day_money_list = OrderBackup::query()->with('info')->with('user')
            ->whereDate('order_dateTime','=',$time)
            ->whereIn('user_id', $children_users)
            ->where('user_id',$user_id)->paginate(10);

        return view('agent.statManager.day_money_statuser',[
            'day_money_list' => $day_money_list,
            'time' => $time,
            'user_id' => $user_id
        ]);
    }
}





