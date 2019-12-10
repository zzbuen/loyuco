<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

/*
 * 房间管理控制器
 * */

class HouseController extends Controller
{
    public function house(){
        $list = Room::query()->get()->toArray();
        return view('manager.house.house',['list'=>$list]);
    }

    public function edit(Request $request)
    {
        $list = Room::query()->find($request->input('id'));
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'minBet' => 'required',
                'maxBet' => 'required',
            ], [
                'minBet.required'  => '最低投注不得为空',
                'maxBet.required'  => '最高投注不得为空',
            ]);

            if ($validator->fails()) {
                $errors = ['success' => false,'msg'=>$validator->errors()->first()];
                return $errors;
            }


            try {
                DB::begintransaction();
                if($request->is_private != 'on'){
                    $list->minBet = $request->input('minBet');
                    $list->maxBet = $request->input('maxBet');
                    $list->conditionmin = $request->input('cond');
                    $list->html = $request->input('html');
                    $list->texts = $request->input('texts');
                    $list->is_private = 0;
                    if (!$list->save()) {
                        throw new \Exception('操作失败');
                    }
                }else{
                    $list->minBet = $request->input('minBet');
                    $list->maxBet = $request->input('maxBet');
                    $list->cipher = $request->input('cipher');
                    $list->conditionmin = $request->input('cond');
                    $list->is_private = 1;
                    $list->html = $request->input('html');
                    $list->texts = $request->input('texts');

                    if (!$list->save()) {
                        throw new \Exception('操作失败');
                    }
                }

                DB::commit();
            } catch (\Exception $e){
                DB::rollBack();
                return ['success' => false,'msg'=>$e->getMessage()];
            }
            return ['success' => true,'msg'=>'编辑成功'];
        }
        if (empty($list)) {
            return '参数错误';
        }
        return view('manager.house.edit',['list'=>$list]);
    }

    public function xe_set(Request $request){
        $list = Room::query()->find($request->input('id'));
        if ($request->isMethod('post')) {
            try {
                DB::begintransaction();
                $list->sum_dxds = $request->input('sum_dxds');
                $list->sum_jz = $request->input('sum_jz');
                $list->sum_sz = $request->input('sum_sz');
                $list->sum_zh = $request->input('sum_zh');
                $list->sum_hll = $request->input('sum_hll');
                $list->sun_bz = $request->input('sun_bz');
                $list->sun_lhh = $request->input('sun_lhh');
                $list->sum_bet = $request->input('sum_bet');
                if (!$list->save()) {
                    throw new \Exception('操作失败');
                }
                DB::commit();
            } catch (\Exception $e){
                DB::rollBack();
                return ['success' => false,'msg'=>$e->getMessage()];
            }
            return ['success' => true,'msg'=>'编辑成功'];
        }
        if (empty($list)) {
            return '参数错误';
        }
        return view('manager.house.xe_set',['list'=>$list]);
    }
}





