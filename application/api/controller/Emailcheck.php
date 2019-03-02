<?php
namespace app\api\controller;
use think\Controller;
use PHPMailer\SendEmail;
/**
 * Created by PhpStorm.
 * User: times
 * Date: 2019/1/7
 * Time: 11:09
 */
class Emailcheck extends Controller{
    private  $obj;
    public function _initialize() {
        $this->obj = model("EmailCheck");
    }

    public function EmailCheck(){
            $email = input('email');
            $username =  session('we_user', '', 'we')['user_name'];
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
            session('we_R_Psw', $use, 'ec');
            $content = " <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"font-family:'微软雅黑',Helvetica,Arial,sans-serif;font-size:14px \" width=\"100%\">
     <tbody>
                <tr>
                    <td style=\"font-family:Helvetica,Arial,sans-serif;font-size:14px;\">
                    <table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\" >
                            <tbody>
                                <tr>
                                    <td>
                                        <p style=\"margin:0;font-size:14px;line-height:24px;font-family:'微软雅黑',Helvetica,Arial,sans-serif;margin-bottom: 20px\"><br>　　　　　　　　　　　　　　　　　　　　　　　　　　尊敬的We-Times.club用户 $username :<br>　　　　　　　　　　　　　　　　　　　　　　　　</p>
                                        <p style=\"color:#000;margin:0;font-size:14px;line-height:24px;font-family:'微软雅黑',Helvetica,Arial,sans-serif;\"><br>　　　　　　　　　　　　　　　　　　　　　　　　　您的邮箱验证码是:<span style=\"color:#2E5CD5; font-size: 40px\"> $code</span> <br>　　　　　　　　　　　　　　　　　　　　　</p>
                                    </td>
                                </tr>
                            </tbody>
                    </table>                                                          
                   </td>
              </tr>
                
   </tbody>
</table>   ";

            SendEmail::SendEmail($email,'We-Times.club邮箱验证',$content);
            return show('1','发送成功','');

    }

    public function EmailCheck2(){
        $retrieve_code = input('code');
        $identity =  session('we_R_Psw', '', 'ec')['identity'];
        $uid = session('we_user', '', 'we')['uid'];
        $data = model('EmailCheck')->getCodeByIdentity($identity);
        if ($retrieve_code == $data['code']){
            $data= [
              'email_status' => 1
            ];
            model('User')->updateByUId($data,$uid);
            $update = model('User')->getUserByUId($uid);
            session('we_user', $update, 'we');

            return show('1','验证成功','');
        }
        else{
            return show('0','验证失败','');
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