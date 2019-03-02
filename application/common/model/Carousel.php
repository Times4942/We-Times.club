<?php
/**
 * Created by PhpStorm.
 * User: times
 * Date: 2018/12/27
 * Time: 18:19
 */
namespace app\common\model;
class Carousel extends BaseModel{
    /**
     * 添加一条新记录
     * @param $save_name，$upload_time,$upload_admin，$status
     */
    public function add($data = [])
    {
        // 如果提交的数据不是数组
        if (!is_array($data)) {
            exception('传递的数据不是数组');
        }
        return $this->data($data)->allowField(true)
            ->save();
    }
    /**
     * 根据id获取详细信息
     * @param $id
     */
    public function getInfoById($id){
        if (!$id){
            exception('ID不合法');
        }
        $data = ['id' => $id];
        return $this->where($data)->find();
    }

    public function SelectInfo($data){
        return $this->where($data)->paginate('5');
    }
    /**
     * 根据id改变状态
     * @param $data $id
     */
    public function ChangeStatus($data,$id){
        return $this->allowField(true)->save($data, ['id'=>$id]);
    }
    /**
     * 根据id删除相关记录id
     * @param $id
     */
    public function deleteCarouselById($id) {
        if(!$id) {
            exception('ID不合法');
        }
        $data = ['id' => $id];
        return $this->where($data)->delete();
    }

    public function getCarouselByStatus(){
        $data = ['status' => 1];
        return $this->where($data)->select();
    }
}

