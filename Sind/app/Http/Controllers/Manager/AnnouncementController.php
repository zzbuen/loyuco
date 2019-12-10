<?php

namespace App\Http\Controllers\Manager;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AnnouncementController extends Controller
{

    public function index(Request $request)
    {
        $announcement_list = Announcement::query()->orderBy("id","desc")->paginate(10);
        return view('manager.announcement.index', [
            'announcement_list' => $announcement_list,
        ]);
    }


    /**
     * 作用：添加时间
     * 作者：信
     * 时间：2018/4/25
     * 修改：暂无
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add(Request $request){
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'title' => 'required|max:150',
                'content' => 'required',
                "show_time" =>"required",
            ], [
                'title.required'  => '请输入标题',
                'title.max'  => '标题最多150个字',
                'content.required'  => '请输入公告内容',

                "show_time.required"=>"请选择时间",
            ]);

            if ($validator->fails()) {
                $errors = ['success' => false,'msg'=>$validator->errors()->first()];
                return $errors;
            }

            try {
                DB::begintransaction();
                $announcement = new Announcement();
                $announcement->title = $request->input('title');
                $announcement->content = $request->input('content');
                $announcement->status = $request->has('status')?1:2;
                $announcement->show_time = $request->input('show_time');
                if (!$announcement->save()) {
                    throw new \Exception('添加失败');
                }
                DB::commit();
            } catch (\Exception $e){
                DB::rollBack();
                return ['success' => false,'msg'=>$e->getMessage()];
            }
            return ['success' => true,'msg'=>'添加成功'];
        }
        return view('manager.announcement.add');
    }


    /**
     * 作用：修改公告
     * 作者：信
     * 时间：2018/4/25
     * 修改：暂无
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function edit(Request $request)
    {
        $announcement = Announcement::query()->find($request->input('id'));
        if ($request->isMethod('post')) {
            if (empty($announcement)) {
                return ['success' => false,'msg'=>'参数错误'];
            }
            $validator = Validator::make($request->all(), [
                'title' => 'required|max:150',
                'content' => 'required|max:240',
                "show_time" =>"required",
            ], [
                'title.required'  => '请输入标题',
                'title.max'  => '标题最多150个字',
                'content.required'  => '请输入公告内容',
                'content.max'  => '公告内容最多240个字',
                "show_time.required"=>"请选择时间",
            ]);

            if ($validator->fails()) {
                $errors = ['success' => false,'msg'=>$validator->errors()->first()];
                return $errors;
            }

            try {
                DB::begintransaction();
                $announcement->title = $request->input('title');
                $announcement->content = $request->input('content');
                $announcement->status = $request->has('status')?1:2;
                $announcement->show_time = $request->input('show_time');
                if (!$announcement->save()) {
                    throw new \Exception('操作失败');
                }
                DB::commit();
            } catch (\Exception $e){
                DB::rollBack();
                return ['success' => false,'msg'=>$e->getMessage()];
            }
            return ['success' => true,'msg'=>'编辑成功'];
        }
        if (empty($announcement)) {
            return '参数错误';
        }
        return view('manager.announcement.edit',['announcement'=>$announcement]);
    }

    public function destroy(Request $request)
    {
        $id = $request->input('id');
        try {
            DB::begintransaction();
            $result = Announcement::query()->where('id', $id)->delete();
            if (!$result) {
                throw new \Exception('操作失败');
            }
            DB::commit();
        } catch (\Exception $e){
            DB::rollBack();
            return ['success' => false,'msg'=>$e->getMessage()];
        }
        return ['success' => true,'msg'=>'操作成功'];
    }

}
