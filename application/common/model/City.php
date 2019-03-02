<?php
namespace app\common\model;
/**
 * Created by PhpStorm.
 * User: times
 * Date: 2018/12/29
 * Time: 18:19
 */
class City extends BaseModel{
    /**
     * 添加一条新记录
     * @param $city_name，$e_name,$parent_id，$status,$create_time,$create_admin
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
     * 查询所有省、市、自治区
     * @param
     */
    public function SearchFirst(){
        $data = [
            'parent_id' => 0
        ];
        return $this->where($data)->paginate('5');
    }
    /**
     * 查询所有省、市、自治区(不分页)
     * @param
     */
    public function SearchFirst2(){
        $data = [
            'parent_id' => 0
        ];
        return $this->where($data)->select();
    }

    /**
     * 查询默认信息
     * @param
     */
    public function SearchDefault(){
        $data = [
            'id' => 1
        ];
        return $this->where($data)->find();
    }

    /**
     * 根据parent_id查询所有子城市
     * @param $parent_id
     */
    public function SearchChild($parent_id){
        $data = [
            'parent_id' => $parent_id
        ];
        return $this->where($data)->select();
    }

    /**
     * 根据parent_id查询所有子城市
     * @param $parent_id
     */
    public function SearchChildByStatus($parent_id){
        $data = [
            'parent_id' => $parent_id,
            'status' => 1
        ];
        return $this->where($data)->select();
    }

    /**
     * 根据id查询父级城市
     * @param $parent_id
     */
    public function getParentNameById($id){
        $data = [
            'id' => $id
        ];
        return $this->where($data)->find();
    }

    /**
     * 根据id改变状态
     * @param $data $id
     */
    public function ChangeStatus($data,$id){
        return $this->allowField(true)->save($data, ['id'=>$id]);
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

    /**
     * 根据id删除相关记录id
     * @param $id
     */
    public function deleteCityById($id) {
        if(!$id) {
            exception('ID不合法');
        }
        $data = ['id' => $id];
        return $this->where($data)->delete();
    }
}