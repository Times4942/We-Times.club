<?php
namespace app\common\model;
/**
 * Created by PhpStorm.
 * User: times
 * Date: 2018/12/24
 * Time: 09:43
 */
class EmailCheck extends BaseModel{
    /**
     * 添加一条新验证记录
     * @param $identity，$email,$code
     */
    public function add($data = []){
        // 如果提交的数据不是数组
        if(!is_array($data)) {
            exception('传递的数据不是数组');
        }
        return $this->data($data)->allowField(true)
            ->save();
    }
    /**
     * 根据身份识别码获取邮箱验证码
     * @param $admin_id
     */
    public function getCodeByIdentity($identity) {
        if(!$identity) {
            exception('身份识别码不合法');
        }
        $data = ['identity' => $identity];
        return $this->where($data)->find();
    }
}