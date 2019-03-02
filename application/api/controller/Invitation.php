<?php
namespace app\api\controller;
use think\Controller;
/**
 * Created by PhpStorm.
 * User: times
 * Date: 2018/12/26
 * Time: 15:06
 */
class Invitation extends Controller{
    private  $obj;
    public function _initialize() {
        $this->obj = model("InvitationCode");
    }
    //http://localhost/We-Times/public/api/Invitation/Search?code=CLPFBX
    public function Search(){
        $code = input('code');
        if(!$code){
            $this->error('参数不合法');
        }
        else{
            $data = $this->obj->getInfoByCode($code);
            if (!$data){
              return show('0','邀请码不存在，请检查邀请码');
            }
            else if($data->status == 1){
                return show('2','验证失败,邀请码已使用');
            }
            else{
                $this->obj->ChangeStatus(['status'=>'1'], $data->id);
                return show('1','验证成功','');
            }
        }
    }
    public function Invitation(){
        $generate = input("generate");
        if(!$generate or $generate == 0) {
            $this->error('输入参数不合法');
        }
        else{
            $admin_name = session('we_admin', '', 'we')['admin_name'];
            $Invitationcode = $this->randCode('6','3');
            $time = time();
            $status = '0';
            $data = ['admin_name' => $admin_name,
                     'Invitationcode' => $Invitationcode,
                     'creation_time' => $time,
                     'status' => $status
            ];
            model('InvitationCode')->add($data);
            return show(1,'生成成功',$Invitationcode);
        }

    }

    /**
    +----------------------------------------------------------
     * 生成随机字符串
    +----------------------------------------------------------
     * @param int       $length  要生成的随机字符串长度
     * @param string    $type    随机码类型：0，数字+大小写字母；1，数字；2，小写字母；3，大写字母；4，特殊字符；-1，数字+大小写字母+特殊字符
    +----------------------------------------------------------
     * @return string
    +----------------------------------------------------------
     */
    function randCode($length = 5, $type = 0) {
        $arr = array(1 => "0123456789", 2 => "abcdefghijklmnopqrstuvwxyz", 3 => "ABCDEFGHIJKLMNOPQRSTUVWXYZ", 4 => "~@#$%^&*(){}[]|");
        if ($type == 0) {
            array_pop($arr);
            $string = implode("", $arr);
        } elseif ($type == "-1") {
            $string = implode("", $arr);
        } else {
            $string = $arr[$type];
        }
        $count = strlen($string) - 1;
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $string[rand(0, $count)];
        }
        return $code;
    }

}