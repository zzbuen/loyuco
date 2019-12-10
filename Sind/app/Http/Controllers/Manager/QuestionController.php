<?php

namespace App\Http\Controllers\Manager;

use App\Models\Category;
use App\Models\MessUser;
use App\Models\User;
use App\Models\UserInfo;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Feedback;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Bet;
use App\Models\Odds;
use App\Models\TemporaryOdds;
use App\Models\Game;
use Illuminate\Support\Facades\Session;

class QuestionController  extends Controller{


    /**
     * 作用：问题反馈
     * 作者：
     * 时间：2018/4/12
     * 修改：信
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function question_back(Request $request){
        $data = MessUser::query()
            ->where(["delete_time"=>0,"user_id"=>"admin"])
            ->with(["send_user"=>function($query){
                $query->select("user_id","username","role_id");
            }])
            ->with(["userinfo"=>function($query){
                $query->select("user_id","mobile_number");
            }])
            ->orderBy("id","desc");

        /*用户名查找*/
        $user_name = $request->user_name;
        if($user_name){
            $data = $data->where("send_user_id",$user_name);
        }

        /*状态查找*/
        $status = $request->status;
        if($status=="0" || $status=="1"){
            $data = $data->where("status",$status);
        }

        $data = $data->paginate(10);
        return view('manager.userManager.question_back ', [
            'data'         => $data,
            "user_name"    => $user_name,
            "status"       => $status
        ]);
    }




    /**
     * 作用：查看问题反馈
     * 作者：信
     * 时间：2018/4/26
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function look_wenti(Request $request){
        $id = $request->input('id');
        $question_info = MessUser::query()
            ->where('mess_user.id',$id)
            ->with(["user"=>function($query){
                $query->select("user_id","username","role_id");
            }])
            ->with(["userinfo"=>function($query){
                $query->select("user_id","mobile_number");
            }])
            ->first();

        MessUser::query()->where("id",$id)->update(["status"=>1]);
        return view('manager.userManager.look_wenti ',[
            'question_info'=>$question_info
        ]);
    }


    /**
     * 作用：回复
     * 作者：信
     * 时间：2018/4/26
     * 修改：暂无
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function replay_qurestion(Request $request){
        /*执行回复*/
        if ($request->isMethod('post')) {
            $user_id    = $request->input('user_id');
            $time       = Carbon::now()->toDateTimeString();
            $title      = $request->input('title');
            $content    = $request->input('huifu');
            $up_arr = [
                'user_id'       => $user_id,
                'send_user_id'  => "admin",
                'title'         => $title,
                'content'       => $content,
                "status"        => 0,
                "type"          => 1,
                "create_time"   => $time

            ];
            $feed_sql = MessUser::query()->insert($up_arr);
            if(!$feed_sql){
                return ['flag'=>false,'msg'=>'回复失败'];
            }
            return ['flag'=>true,'msg'=>'回复成功'];
        }




        /*跳到回复页面*/
        $id = $request->input('id');
        $question_info = MessUser::query()
            ->where('mess_user.id',$id)
            ->with(["user"=>function($query){
                $query->select("user_id","username","role_id");
            }])
            ->with(["userinfo"=>function($query){
                $query->select("user_id","mobile_number");
            }])
            ->first()
            ->toArray();

        return view('manager.userManager.replay_question ', [
            'question_info'=>$question_info
        ]);
    }


    /**
     * 作用：删除问题反馈
     * 作者：信
     * 时间：2018/4/12
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function delete_question(Request $request){
        $id = $request->input("id")?$request->input("id"):0;
        $res = MessUser::query()->where("id",$id)->update(["delete_time"=>time()]);
        if($res){
            return ["code"=>1,"msg"=>"删除成功"];
        }
        return ["code"=>0,"msg"=>"删除失败，请稍后再试"];
    }


    /**
     * 作用：多选删除
     * 作者：信
     * 时间：2018/6/21
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function delete_question_duo(Request $request){
        $data = $request->quanxuan;
        $data = explode(",",$data);
        $res = MessUser::query()->whereIn("id",$data)->update(["delete_time"=>time(),"delete_time2"=>time()]);
        if($res){
            return ["code"=>1,"msg"=>"恭喜您！删除成功"];
        }
        return ["code"=>0,"msg"=>"删除失败"];
    }









    /**
     * 作用：消息记录
     * 作者：信
     * 时间：2018/4/30
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function xiaoxi(Request $request){
        $data = MessUser::query()
            ->where("delete_time",0)
            ->where("user_id","!=","admin")
            ->where("send_user_id","!=","admin")
            ->where("type",1)
            ->with(["user"=>function($query){
                $query->select("user_id","username","role_id");
            }])
            ->with(["send_user"=>function($query){
                $query->select("user_id","username");
            }])
            ->with(["userinfo"=>function($query){
                $query->select("user_id","mobile_number");
            }]);

        /*发送者用户查询*/
        $user_name = $request->user_name;
        if($user_name){
            $data = $data->where("send_user_id",$user_name);
        }

        /*接收者查询*/
        $jieshouzhe = $request->jieshouzhe;
        if($jieshouzhe){
            $data = $data->where("user_id",$jieshouzhe);
        }

        /*状态查找*/
        $status = $request->status;
        if($status=="0" || $status=="1"){
            $data = $data->where("status",$status);
        }

        $data = $data->orderBy("id","desc")->paginate(15);
        return view('manager.userManager.xiaoxi ', [
            'data'          => $data,
            "user_name"     => $user_name,
            "jieshouzhe"    => $jieshouzhe,
            "status"        => $status
        ]);
    }






    /**
     * 作用：发消息
     * 作者：信
     * 时间：2018/5/18
     * 修改：暂无
     */
    public function send_xiaoxi(Request $request){
        set_time_limit(0);
        $user_id = $request->user_id;
        if($user_id=="yiji"){
            $res = UserInfo::query()
                ->with(["user"=>function($query){
                    $query->select("user_id","username");
                }])
                ->where("parent_user_id","0")
                ->get()
                ->toArray();
            $data = [];
            foreach ($res as $key=>$value){
                $data[] = $value["user"]["username"];
            }
        }else{
            $data = $this->getChildren($user_id);
            $data = substr($data,1);
            $data =explode(",",$data);
        }

        return view("manager.userManager.send_xiaoxi",["data"=>$data]);
    }


    /**
     * 作用：发消息ajax
     * 作者：信
     * 时间：2018/5/18
     * 修改：暂无
     */
    public function send_xiaoxi_ajax(Request $request){
        $arr = [];
        $data   = $request->all();
        $biaoti = $data["biaoti"];
        $neiron = $data["neiron"];
        $str    = substr($data["str"],1);
        $str    = explode(",",$str);
        foreach ($str as $key=>$value){
            $arr_one = [
                "user_id"       => $value,
                "send_user_id"   => "admin",
                "title"         => $biaoti,
                "content"       => $neiron,
                "status"        => 0,
                "type"          => 1,
                "create_time"   => date("Y-m-d H:i:s",time()),
            ];
            $arr[] = $arr_one;
        }

        $res = MessUser::query()->insert($arr);
        if($res){
            return ["code"=>1,"msg"=>"消息发送成功"];
        }
        return ["code"=>0,"msg"=>"消息发送失败"];
    }





    public function getChildren ($user_id){
        $ids = '';
        $result = UserInfo::query()
            ->with(["user"=>function($query){
                $query->select("user_id","username");
            }])
            ->where("parent_user_id",$user_id)
            ->get();

        if ($result){
            foreach ($result as $key=>$val) {
                $ids .= ','.$val["user"]["username"];
                $ids .= $this->getChildren ($val["user_id"]);
            }
        }
        return $ids;
    }




    /**
     * 作用：查看消息
     * 作者：信
     * 时间：2018/4/26
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function look_xiaoxi(Request $request){
        $id = $request->input('id');
        $question_info = MessUser::query()
            ->where('mess_user.id',$id)
            ->with(["user"=>function($query){
                $query->select("user_id","username","role_id");
            }])
            ->with(["send_user"=>function($query){
                $query->select("user_id","username");
            }])
            ->with(["userinfo"=>function($query){
                $query->select("user_id","mobile_number");
            }])
            ->first();

        return view('manager.userManager.look_xiaoxi ',[
            'question_info'=>$question_info
        ]);
    }


    /**
     * 作用：查看系统消息
     * 作者：信
     * 时间：2018/4/26
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function look_xitongxiaoxi(Request $request){
        $id = $request->input('id');
        $question_info = MessUser::query()
            ->where('mess_user.id',$id)
            ->with(["user"=>function($query){
                $query->select("user_id","username","role_id");
            }])
            ->with(["send_user"=>function($query){
                $query->select("user_id","username");
            }])
            ->with(["userinfo"=>function($query){
                $query->select("user_id","mobile_number");
            }])
            ->first();

        return view('manager.userManager.look_xitongxiaoxi ',[
            'question_info'=>$question_info
        ]);
    }




    /**
     * 作用：实时查看是否有人反馈消息
     * 作者：信
     * 时间：2018/5/22
     * 修改：暂无
     * @return array
     */
    public function question_tishi(){
        $data = MessUser::query()
            ->where(["delete_time"=>"0","user_id"=>"admin","status"=>0])
            ->first();

        if($data){
            return ["code"=>1,"msg"=>"有人反馈问题了","id"=>$data["id"]];
        }
        return ["code"=>0,"msg"=>"没人反馈问题"];
    }




    /**
     * 作用：系统消息
     * 作者：
     * 时间：2018/6/21
     * 修改：信
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function xitongxiaoxi(Request $request){
        $data = MessUser::query()
            ->where(["delete_time"=>0,"send_user_id"=>"admin"])
            ->with(["user"=>function($query){
                $query->select("user_id","username","role_id");
            }])
            ->with(["userinfo"=>function($query){
                $query->select("user_id","mobile_number");
            }])
            ->orderBy("id","desc");

        /*用户名查找*/
        $user_name = $request->user_name;
        if($user_name){
            $data = $data->where("user_id",$user_name);
        }

        /*状态查找*/
        $status = $request->status;
        if($status=="0" || $status=="1"){
            $data = $data->where("status",$status);
        }

        $data = $data->paginate(10);
        return view('manager.userManager.xitongxiaoxi ', [
            'data'         => $data,
            "user_name"    => $user_name,
            "status"       => $status
        ]);
    }


}





