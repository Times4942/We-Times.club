<?php
namespace app\api\controller;
use think\Controller;
/**
 * Created by PhpStorm.
 * User: times
 * Date: 2018/12/28
 * Time: 11:45
 */
class Carousel extends Controller{
    private  $obj;
    public function _initialize() {
        $this->obj = model("Carousel");
    }

    public function Delete(){
        $id = input('id');
        if(!$id){
            $this->error('参数不合法');
        }
        else{
            $Filename = $this->obj->getInfoById($id)['save_name'];
            $path = ROOT_PATH . 'public' . DS . 'static' . DS . 'upload' . DS . 'images' . DS . 'carousel'. DS . $Filename;
            if (file_exists($path)){
                unlink($path);
                $this->obj->deleteCarouselById($id);
                return show('1','删除成功','');
            }
            else{
                return show('0','删除失败','');
            }
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
}