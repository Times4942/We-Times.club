<?php
namespace app\api\controller;
use think\Controller;
/**
 * Created by PhpStorm.
 * User: times
 * Date: 2018/12/24
 * Time: 13:43
 */
class Queryuser extends Controller{
    private  $obj;
    public function _initialize() {
        $this->obj = model("Admin");
    }

//http://localhost/We-Times/public/api/queryuser/getAdminByAdminName?admin_name=mazhihao
    public function getAdminByAdminName(){
        $adminname = input('admin_name');
        if(!$adminname) {
            $this->error('adminname不合法');
        }
        $info = $this->obj->getAdminByAdminname($adminname);

        if ($info){
            return show(1,'用户已存在','');
        }
        else{
            return show(0,'可以注册','');
        }
    }
}