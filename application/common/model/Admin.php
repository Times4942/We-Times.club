<?php
namespace app\common\model;


/**
 * Created by PhpStorm.
 * User: times
 * Date: 2018/12/17
 * Time: 18:09
 */
class Admin extends BaseModel{
    public function add($data = []){
        // 如果提交的数据不是数组
        if(!is_array($data)) {
            exception('传递的数据不是数组');
        }
        return $this->data($data)->allowField(true)
            ->save();
    }

    /**
     * 根据用户名获取用户信息
     * @param $adminname
     */
    public function getAdminByAdminname($adminname) {
        if(!$adminname) {
            exception('用户名不合法');
        }

        $data = ['admin_name' => $adminname];
        return $this->where($data)->find();
    }

    /**
     * 根据用户ID获取用户信息
     * @param $admin_id
     */
    public function getAdminByAdminId($admin_id) {
        if(!$admin_id) {
            exception('ID不合法');
        }

        $data = ['admin_id' => $admin_id];
        return $this->where($data)->find();
    }
}
