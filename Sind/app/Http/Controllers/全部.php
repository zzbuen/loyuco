<?php
/**
 * Created by 信.
 * Date: 2018/4/4
 */

/**
 * 作用：天津时时彩开奖数据
 * 作者：信
 * 时间：2018/4/2
 * 修改：暂无
 * @return array|string
 */
class hahah {


public function tianjin(){
    $data = DrawResultTianjin::query()
        ->leftJoin("game","game_id","=","game.id")
        ->leftJoin("game_type","draw_result_tianjin.type","=","game_type.id")
        ->select("draw_result_tianjin.*","game_type.typeName","game.name")
        ->get()
        ->toArray();

    if(!empty($data)){
        return $data;
    }
    return false;
}


/**
 * 作用：新疆时时彩开奖数据
 * 作者：信
 * 时间：2018/4/2
 * 修改：暂无
 * @return array|string
 */
public function xinjiang(){
    $data = DrawResultXinjiang::query()
        ->leftJoin("game","game_id","=","game.id")
        ->leftJoin("game_type","draw_result_xinjiang.type","=","game_type.id")
        ->select("draw_result_xinjiang.*","game_type.typeName","game.name")
        ->get()
        ->toArray();

    if(!empty($data)){
        return $data;
    }
    return false;
}


/**
 * 作用：腾讯分分彩开奖数据
 * 作者：信
 * 时间：2018/4/2
 * 修改：暂无
 * @return array|string
 */
public function tengxun(){
    $data = DrawResultTengxun::query()
        ->leftJoin("game","game_id","=","game.id")
        ->leftJoin("game_type","draw_result_tengxun.type","=","game_type.id")
        ->select("draw_result_tengxun.*","game_type.typeName","game.name")
        ->get()
        ->toArray();

    if(!empty($data)){
        return $data;
    }
    return false;
}


/**
 * 作用：欧洲分分彩开奖数据
 * 作者：信
 * 时间：2018/4/2
 * 修改：暂无
 * @return array|string
 */
public function ouzhou(){
    $data = DrawResultOuzhou::query()
        ->leftJoin("game","game_id","=","game.id")
        ->leftJoin("game_type","draw_result_ouzhou.type","=","game_type.id")
        ->select("draw_result_ouzhou.*","game_type.typeName","game.name")
        ->get()
        ->toArray();

    if(!empty($data)){
        return $data;
    }
    return false;
}


/**
 * 作用：北京五分彩开奖数据
 * 作者：信
 * 时间：2018/4/2
 * 修改：暂无
 * @return array|string
 */
public function beijing(){
    $data = DrawResultBeijing::query()
        ->leftJoin("game","game_id","=","game.id")
        ->leftJoin("game_type","draw_result_beijing.type","=","game_type.id")
        ->select("draw_result_beijing.*","game_type.typeName","game.name")
        ->get()
        ->toArray();

    if(!empty($data)){
        return $data;
    }
    return false;
}


/**
 * 作用：韩国1.5分彩开奖数据
 * 作者：信
 * 时间：2018/4/2
 * 修改：暂无
 * @return array|string
 */
public function hanguo(){
    $data = DrawResultHanguo::query()
        ->leftJoin("game","game_id","=","game.id")
        ->leftJoin("game_type","draw_result_hanguo.type","=","game_type.id")
        ->select("draw_result_hanguo.*","game_type.typeName","game.name")
        ->get()
        ->toArray();

    if(!empty($data)){
        return $data;
    }
    return false;
}


/**
 * 作用：新加坡2分彩开奖数据
 * 作者：信
 * 时间：2018/4/2
 * 修改：暂无
 * @return array|string
 */
public function xinjiapo(){
    $data = DrawResultXinjiapo::query()
        ->leftJoin("game","game_id","=","game.id")
        ->leftJoin("game_type","draw_result_xinjiapo.type","=","game_type.id")
        ->select("draw_result_xinjiapo.*","game_type.typeName","game.name")
        ->get()
        ->toArray();

    if(!empty($data)){
        return $data;
    }
    return false;
}


/**
 * 作用：河内1分彩开奖数据
 * 作者：信
 * 时间：2018/4/2
 * 修改：暂无
 * @return array|string
 */
public function henei(){
    $data = DrawResultHenei::query()
        ->leftJoin("game","game_id","=","game.id")
        ->leftJoin("game_type","draw_result_henei.type","=","game_type.id")
        ->select("draw_result_henei.*","game_type.typeName","game.name")
        ->get()
        ->toArray();

    if(!empty($data)){
        return $data;
    }
    return false;
}


















    /**
     * 作用：天津时时彩手动开奖
     * 作者：信
     * 时间：2018/4/3
     * 修改：暂无
     * @param $id
     * @param $result
     * @param $time
     * @return array
     */
    public function set_result_tianjin($id,$result,$time){
        if(time()>$time){
            $res = DrawResultTianjin::query()
                ->where("id",$id)
                ->update(["result"=>$result,"res_status"=>2]);

            if($res){
                return  ["code"=>1,"msg"=>"开奖设置成功"];
            }
            return  ["code"=>0,"msg"=>"开奖设置失败"];
        }
        /*时间未到开奖时间，只更新数值*/
        $res = DrawResultTianjin::query()
            ->where("id",$id)
            ->update(["result"=>$result]);

        if($res){
            return  ["code"=>1,"msg"=>"开奖设置成功"];
        }
        return  ["code"=>0,"msg"=>"开奖设置失败"];
    }


    /**
     * 作用：新疆时时彩手动开奖
     * 作者：信
     * 时间：2018/4/3
     * 修改：暂无
     * @param $id
     * @param $result
     * @param $time
     * @return array
     */
    public function set_result_xinjiang($id,$result,$time){
        if(time()>$time){
            $res = DrawResultXinjiang::query()
                ->where("id",$id)
                ->update(["result"=>$result,"res_status"=>2]);

            if($res){
                return  ["code"=>1,"msg"=>"开奖设置成功"];
            }
            return  ["code"=>0,"msg"=>"开奖设置失败"];
        }
        /*时间未到开奖时间，只更新数值*/
        $res = DrawResultXinjiang::query()
            ->where("id",$id)
            ->update(["result"=>$result]);

        if($res){
            return  ["code"=>1,"msg"=>"开奖设置成功"];
        }
        return  ["code"=>0,"msg"=>"开奖设置失败"];
    }


    /**
     * 作用：腾讯时时彩手动开奖
     * 作者：信
     * 时间：2018/4/3
     * 修改：暂无
     * @param $id
     * @param $result
     * @param $time
     * @return array
     */
    public function set_result_tengxun($id,$result,$time){
        if(time()>$time){
            $res = DrawResultTengxun::query()
                ->where("id",$id)
                ->update(["result"=>$result,"res_status"=>2]);

            if($res){
                return  ["code"=>1,"msg"=>"开奖设置成功"];
            }
            return  ["code"=>0,"msg"=>"开奖设置失败"];
        }
        /*时间未到开奖时间，只更新数值*/
        $res = DrawResultTengxun::query()
            ->where("id",$id)
            ->update(["result"=>$result]);

        if($res){
            return  ["code"=>1,"msg"=>"开奖设置成功"];
        }
        return  ["code"=>0,"msg"=>"开奖设置失败"];
    }

    /**
     * 作用：欧洲时时彩手动开奖
     * 作者：信
     * 时间：2018/4/3
     * 修改：暂无
     * @param $id
     * @param $result
     * @param $time
     * @return array
     */
    public function set_result_ouzhou($id,$result,$time){
        if(time()>$time){
            $res = DrawResultOuzhou::query()
                ->where("id",$id)
                ->update(["result"=>$result,"res_status"=>2]);

            if($res){
                return  ["code"=>1,"msg"=>"开奖设置成功"];
            }
            return  ["code"=>0,"msg"=>"开奖设置失败"];
        }
        /*时间未到开奖时间，只更新数值*/
        $res = DrawResultOuzhou::query()
            ->where("id",$id)
            ->update(["result"=>$result]);

        if($res){
            return  ["code"=>1,"msg"=>"开奖设置成功"];
        }
        return  ["code"=>0,"msg"=>"开奖设置失败"];
    }


    /**
     * 作用：北京时时彩手动开奖
     * 作者：信
     * 时间：2018/4/3
     * 修改：暂无
     * @param $id
     * @param $result
     * @param $time
     * @return array
     */
    public function set_result_beijing($id,$result,$time){
        if(time()>$time){
            $res = DrawResultBeijing::query()
                ->where("id",$id)
                ->update(["result"=>$result,"res_status"=>2]);

            if($res){
                return  ["code"=>1,"msg"=>"开奖设置成功"];
            }
            return  ["code"=>0,"msg"=>"开奖设置失败"];
        }
        /*时间未到开奖时间，只更新数值*/
        $res = DrawResultBeijing::query()
            ->where("id",$id)
            ->update(["result"=>$result]);

        if($res){
            return  ["code"=>1,"msg"=>"开奖设置成功"];
        }
        return  ["code"=>0,"msg"=>"开奖设置失败"];
    }


    /**
     * 作用：韩国时时彩手动开奖
     * 作者：信
     * 时间：2018/4/3
     * 修改：暂无
     * @param $id
     * @param $result
     * @param $time
     * @return array
     */
    public function set_result_hanguo($id,$result,$time){
        if(time()>$time){
            $res = DrawResultHanguo::query()
                ->where("id",$id)
                ->update(["result"=>$result,"res_status"=>2]);

            if($res){
                return  ["code"=>1,"msg"=>"开奖设置成功"];
            }
            return  ["code"=>0,"msg"=>"开奖设置失败"];
        }
        /*时间未到开奖时间，只更新数值*/
        $res = DrawResultHanguo::query()
            ->where("id",$id)
            ->update(["result"=>$result]);

        if($res){
            return  ["code"=>1,"msg"=>"开奖设置成功"];
        }
        return  ["code"=>0,"msg"=>"开奖设置失败"];
    }


    /**
     * 作用：新加披时时彩手动开奖
     * 作者：信
     * 时间：2018/4/3
     * 修改：暂无
     * @param $id
     * @param $result
     * @param $time
     * @return array
     */
    public function set_result_xinjiapo($id,$result,$time){
        if(time()>$time){
            $res = DrawResultXinjiapo::query()
                ->where("id",$id)
                ->update(["result"=>$result,"res_status"=>2]);

            if($res){
                return  ["code"=>1,"msg"=>"开奖设置成功"];
            }
            return  ["code"=>0,"msg"=>"开奖设置失败"];
        }
        /*时间未到开奖时间，只更新数值*/
        $res = DrawResultXinjiapo::query()
            ->where("id",$id)
            ->update(["result"=>$result]);

        if($res){
            return  ["code"=>1,"msg"=>"开奖设置成功"];
        }
        return  ["code"=>0,"msg"=>"开奖设置失败"];
    }


    /**
     * 作用：河内时时彩手动开奖
     * 作者：信
     * 时间：2018/4/3
     * 修改：暂无
     * @param $id
     * @param $result
     * @param $time
     * @return array
     */
    public function set_result_henei($id,$result,$time){
        if(time()>$time){
            $res = DrawResultHenei::query()
                ->where("id",$id)
                ->update(["result"=>$result,"res_status"=>2]);

            if($res){
                return  ["code"=>1,"msg"=>"开奖设置成功"];
            }
            return  ["code"=>0,"msg"=>"开奖设置失败"];
        }
        /*时间未到开奖时间，只更新数值*/
        $res = DrawResultHenei::query()
            ->where("id",$id)
            ->update(["result"=>$result]);

        if($res){
            return  ["code"=>1,"msg"=>"开奖设置成功"];
        }
        return  ["code"=>0,"msg"=>"开奖设置失败"];
    }










}