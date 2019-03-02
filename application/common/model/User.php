<?php
namespace app\common\model;

/**
 * Created by PhpStorm.
 * User: times
 * Date: 2019/1/1
 * Time: 11:10
 */
class User extends BaseModel{
    public function add($data = []){
        // 如果提交的数据不是数组
        if(!is_array($data)) {
            exception('传递的数据不是数组');
        }
        return $this->data($data)->allowField(true)
            ->save();
    }
    public function getUserByEmail($email){
        if(!$email) {
            exception('Email不合法');
        }
        $data = ['email' => $email];
        return $this->where($data)->find();
    }

    public function getUserByUId($uid){
        if(!$uid) {
            exception('Email不合法');
        }
        $data = ['uid' => $uid];
        return $this->where($data)->find();
    }

    /**
     * 根据用户名获取用户信息
     * @param username
     */
    public function getUserByUsername($username) {
        if(!$username) {
            exception('用户名不合法');
        }

        $data = ['user_name' => $username];
        return $this->where($data)->find();
    }
}