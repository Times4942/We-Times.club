<?php
namespace app\common\model;
/**
 * Created by PhpStorm.
 * User: times
 * Date: 2018/12/26
 * Time: 15:09
 */
class InvitationCode extends BaseModel{
    /**
     * 添加一条新记录
     * @param $Invitationcode，$is_admin,$creation_time，$status
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
     * 根据用户名获取邀请码信息
     * @param $admin_name
     */
    public function getCodeByAdminName($admin_name) {
        if(!$admin_name) {
            exception('用户名不合法');
        }
        $data = ['admin_name' => $admin_name];
        return $this->where($data)->paginate(5);
    }

    /**
     * 根据邀请码获取详细信息
     * @param $Invitationcode
     */
    public function getInfoByCode($code){
        if (!$code){
            exception('邀请码格式不合法');
        }
        $data = ['Invitationcode' => $code];
        return $this->where($data)->find();
    }

    public function ChangeStatus($data,$id){
            return $this->allowField(true)->save($data, ['id'=>$id]);
    }
}