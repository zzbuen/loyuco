<?php
/**
 * Created by 信.
 * Date: 2018/4/16
 */
namespace App\Http\Controllers\Manager;
use App\Http\Controllers\Controller;
use App\Models\Gathering;
use App\Models\Loginlog;
use App\Models\SystemZfValue;
use Illuminate\Http\Request;


class GatheringController extends Controller{

    /**
     * 作用：所有收款设置信息
     * 作者：信
     * 时间：2018/4/16
     * 修改：暂无
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function shoukuan(Request $request){
        $data = SystemZfValue::all()->toArray();
       return view("manager.system.shoukuan",["data"=>$data]);
    }


    /**
     * 作用：更改收款信息
     * 作者：信
     * 时间：2018/4/16
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function change_shoukuan(Request $request){
        $data = $request->all();
        $res = SystemZfValue::query()->where("key",$data["id"])->update(["value"=>$data["val"]]);
        if($res){
            return ["code"=>1,"msg"=>"设置成功"];
        }
        return ["code"=>0,"msg"=>"设置失败"];
    }






    /**
     * 作用：上传图片
     * 作者：信
     * 时间：2018/5/25
     * 修改：暂无
     * @param Request $request
     * @return array
     */
    public function set_img(Request $request){
        $file = $request->img;
        if(!$file){
            return ["code"=>0,"msg"=>"未选择新图片"];
        }
        $suffix             = $file->getClientOriginalExtension();         /*文件后缀*/
        $allowed_extensions = ["jpeg","png", "jpg", "gif"];
        if ($suffix && !in_array($suffix, $allowed_extensions)) {
            return ["code"=>0,"msg"=>"请上传jpeg/png/jpg/gif类图片"];
        }
        $img_path   = 'images/robot/';
        $fileName   = $this->make_file_url($suffix);
        $filePath   = asset($img_path.$fileName);
        $file->move($img_path, $fileName);
        return ["code"=>1,"msg"=>$filePath];
    }






    /**
     * 作用：生成文件名称和路径
     * 作者：信
     * 时间：2018/5/25
     * 修改：暂无
     * @param $suffix   "后缀"
     * @return string   "最终路径"
     */
    public function make_file_url($suffix){
        $fileName = str_random(10).'.'.$suffix;
        if(file_exists($fileName)){
            $fileName = $this->make_file_url($suffix);
        }
        return $fileName;
    }




}