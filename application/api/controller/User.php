<?php
namespace app\api\controller;
use think\Controller;
use PHPMailer\SendEmail;
/**
 * Created by PhpStorm.
 * User: times
 * Date: 2019/1/2
 * Time: 13:38
 */
class User extends Controller{
    private  $obj;
    public function _initialize() {
        $this->obj = model("User");
    }

    public function CheckSubmit(){
        //http://localhost/We-Times/public/api/User/CheckSubmit?uid=1
        $uid = input('uid');
        if (!$uid){
            $this->error('参数不合法');
        }
        else{
            $status = $this ->obj->getUserByUId($uid)['status'];
            if ($status == 1){
                return show('1','验证成功','');
            }
            else{
                return show('0','验证失败','');
            }

        }
    }

    public function EmailSend(){
        $email = input('email');
        $username = model('User')->getUserByEmail($email)['user_name'];
        $identity = $this->randCode('10','-1');
        $code = $this->randCode('5','0');
        $alldata = ['identity' => $identity,
            'email' => $email,
            'code' => $code,
            'modification_time' => time(),
            'username' => $username
        ];
        $use = ['username' => $username,
            'identity' => $identity
        ];
        model('EmailCheck')->add($alldata);
        session('we_R_Psw', $use, 'rp1');
        $content = " <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"font-family:'微软雅黑',Helvetica,Arial,sans-serif;font-size:14px \" width=\"100%\">
     <tbody>
                <tr>
                    <td style=\"font-family:Helvetica,Arial,sans-serif;font-size:14px;\">
                    <table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\" >
                            <tbody>
                                <tr>
                                    <td>
                                        <p style=\"margin:0;font-size:14px;line-height:24px;font-family:'微软雅黑',Helvetica,Arial,sans-serif;margin-bottom: 20px\"><br>　　　　　　　　　　　　　　　　　　　　　　　　　　尊敬的We-Times.club用户 $username :<br>　　　　　　　　　　　　　　　　　　　　　　　　</p>
                                        <p style=\"color:#000;margin:0;font-size:14px;line-height:24px;font-family:'微软雅黑',Helvetica,Arial,sans-serif;\"><br>　　　　　　　　　　　　　　　　　　　　　　　　　您的密码找回验证码是:<span style=\"color:#2E5CD5; font-size: 40px\"> $code</span> <br>　　　　　　　　　　　　　　　　　　　　　</p>
                                    </td>
                                </tr>
                            </tbody>
                    </table>                                                          
                   </td>
              </tr>
                
   </tbody>
</table>   ";

        SendEmail::SendEmail($email,'We-Times.club密码找回',$content);
        return show('1','发送成功','');
    }

    public function CodeCheck(){
        $retrieve_code = input('code');
        $identity =  session('we_R_Psw', '', 'rp1')['identity'];
        $data = model('EmailCheck')->getCodeByIdentity($identity);
        if ($retrieve_code == $data['code']){
            return show('1','验证成功','');
        }
        else{
            return show('0','验证失败','');
        }
    }

    public function Icon(){
        $file = request()->file('file');
        $info = $file->rule("uniqid")->move(ROOT_PATH . 'public' . DS . 'static' . DS . 'upload' . DS . 'images' . DS . 'icon');
        $save_name = $info->getFilename();
        $uid = session('we_user', '', 'we')['uid'];
        $data = [
            'icon' => $save_name,

        ];
        model('User')->updateByUId($data,$uid);
        session('we_user', '', 'we')['icon'] = $save_name;
        return show('1','上传成功',$data);
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

