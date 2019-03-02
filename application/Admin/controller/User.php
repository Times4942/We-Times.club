<?php
/**
 * Created by PhpStorm.
 * User: times
 * Date: 2018/12/17
 * Time: 14:38
 */
namespace app\Admin\controller;
use PHPMailer\SendEmail;
Class User extends Base {
    public function Register(){
        if (request()->isPost()){
            $data =input('post.');
            $data['password'] = md5($data['password']);
            $code = $data['code'];
            try {
                $res = model('admin')->add($data);
            }catch (\Exception $e) {
                $this->error($e->getMessage());
            }
            if($res) {
                $email = $data['email'];
                $name = $data['admin_name'];
                $content = "亲爱的$name,欢迎您成为WeTimes.club管理员!请牢记您的密码~";
                SendEmail::SendEmail($email,'欢迎注册We-Times.club管理员账户',$content);
                $this->success('注册成功');
            }else{
                $this->error('注册失败');
            }
        }
        else{
            return $this->fetch();
        }

    }

    public function Login(){
         if (request()->isPost()){
            $data = input('post.');
            try {
                $admin = model('Admin')->getAdminByAdminname($data['admin_name']);
            }catch (\Exception $e){
                $this->error($e->getMessage());
            }
            // 判定密码是否合理
            if(md5($data['password']) != $admin->password) {
                $this->error('密码不正确');
            }
            // 登录成功
            model('Admin')->updateById(['last_login_time'=>time()], $admin->admin_id);
            $clientIp = $this->request->ip();

            model('Admin')->updateById(['clientIp'=>$clientIp], $admin->admin_id);

            //把用户信息记录到session
            session('we_admin', $admin, 'we');

            $this->success('登录成功', url('Index/index'));
        }
        else{
            return $this->fetch();
        }
    }

    public function ChangePsw(){

        if (request()->isPost()){
            $data = input('post.');
            try {
                $userdata = session('we_admin', '', 'we');
                $admin = model('Admin')->getAdminByAdminID($userdata['admin_id']);
            }catch (\Exception $e){
                $this->error($e->getMessage());
            }
            if(md5($data['oldpassword']) != $admin->password) {
                $this->error('原密码输入不正确');
            }
            else{
                model('Admin')->updateById(['password'=>md5($data['password'])], $userdata->admin_id);
                $this->success('修改成功');
            }
        }
        else{
            return $this->fetch();
        }

    }

    public function RetrievePsw(){
        if (request()->isPost()){
            $data = input('post.');

                $email = $data['email'];
                $username = $data['username'];
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
                session('we_R_Psw', $use, 'rp');
                $content = " <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"font-family:'微软雅黑',Helvetica,Arial,sans-serif;font-size:14px \" width=\"100%\">
     <tbody>
                <tr>
                    <td style=\"font-family:Helvetica,Arial,sans-serif;font-size:14px;\">
                    <table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\" >
                            <tbody>
                                <tr>
                                    <td>
                                        <p style=\"margin:0;font-size:14px;line-height:24px;font-family:'微软雅黑',Helvetica,Arial,sans-serif;margin-bottom: 20px\"><br>　　　　　　　　　　　　　　　　　　　　　　　　　　尊敬的We-Times.club管理员 $username :<br>　　　　　　　　　　　　　　　　　　　　　　　　</p>
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
                $this->redirect(url('User/RetrievePswVal'));

        }
        else{
            return $this->fetch();
        }

    }

    public function RetrievePswVal(){
        if (request()->isPost()){
            $retrieve_code = input('post.')['retrieve_code'];
            $identity =  session('we_R_Psw', '', 'rp')['identity'];
            $data = model('EmailCheck')->getCodeByIdentity($identity);
            if ($retrieve_code == $data['code']){
                $this->redirect(url('User/ChangePswRet'));
            }
            else{
               $this->error('验证错误，请重新输入');
            }

        }
        else{
            return $this->fetch();
        }


    }

    public function ChangePswRet(){
        if (request()->isPost()){
            $password = input('post.')['password'];
            $admin = model('Admin')->getAdminByAdminname(session('we_R_Psw', '', 'rp')['username']);
            model('Admin')->updateById(['password'=>md5($password)], $admin->admin_id);
                $this->success('修改成功','User/Login');
                session(null, 'rp');
        }
        else{
            return $this->fetch();
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