<?php
namespace app\api\controller;
use think\Controller;
/**
 * Created by PhpStorm.
 * User: times
 * Date: 2018/12/29
 * Time: 19:48
 */
class City extends Controller{
    private  $obj;
    public function _initialize() {
        $this->obj = model("City");
    }
    //http://localhost/We-Times/public/api/City/SearchChild?parent_id=1
    public function SearchChild(){
        $parent_id = input('parent_id');
        if(!$parent_id){
            $this->error('参数不合法');
        }
        else{
            $data = $this->obj->SearchChild($parent_id);
            if ($data){
                return show(1,'success', $data);
            }
            else return show(0,'error', '');
        }
    }

    public function SearchChildByStatus(){
        $parent_id = input('parent_id');
        if(!$parent_id){
            $this->error('参数不合法');
        }
        else{
            $data = $this->obj->SearchChildByStatus($parent_id);
            if ($data){
                return show(1,'success', $data);
            }
            else return show(0,'error', '');
        }
    }
    public function getParentNameById(){
        $id = input('parent_id');
        if(!$id){
            $this->error('参数不合法');
        }
        else{
            $parent_name = $this->obj->getParentNameById($id)['city_name'];
            if ($parent_name){
                return show(1,'success', $parent_name);
            }
            else return show(0,'error', '');
        }
    }

    public function Change(){
        $id = input('id');
        if (!$id){
            $this->error('参数不合法');
        }
        else{
            $oldstatus = $this->obj->getInfoById($id)['status'];
            if ($oldstatus == 0){
                $data = [
                    'status' => 1,
                ];
                $this->obj->ChangeStatus($data,$id);
                return show('1','修改成功','1');
            }
            else if($oldstatus == 1){
                $data = [
                    'status' => 0,
                ];
                $this->obj->ChangeStatus($data,$id);
                return show('1','修改成功','0');
            }
        }
    }

    public function Delete(){
        $id = input('id');
        if(!$id){
            $this->error('参数不合法');
        }
        else{
           $this->obj->deleteCityById($id);
           return show('1','删除成功','');
        }
    }

    public function GetFirstCity(){
        $first = model('City')->SearchFirst2();
        if($first){
            return show('1','获取成功',$first);
        }
    }


}