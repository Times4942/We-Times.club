<?php
namespace app\Index\controller;
use think\Session;
use PHPMailer\SendEmail;
class User extends Base
{
    public function Login(){
        if (request()->isPost()){
            $data = input('post.');
            try {
                $user = model('User')->getUserByEmail($data['email']);
            }catch (\Exception $e){
                $this->error($e->getMessage());
            }
            // 判定密码是否合理
            if(md5($data['password']) != $user->password) {
                $this->error('密码不正确');
            }
            // 登录成功
            model('User')->updateByUId(['last_login_time'=>time()], $user->uid);

            //把用户信息记录到session
            session('we_user', $user, 'we');

            $this->success('登录成功', url('Index/index'));
        }
        else{
            return $this->fetch();
        }
    }

    public function Register()
    {
        if (request()->isPost()) {
            $data =input('post.');
            $alldata = [
                'user_name' => $data['user_name'],
                'password' => md5($data['password']),
                'email' => $data['email'],
                'email_status' => 0,
                'reg_time' => time(),
                'status' => 0
            ];
            model('user')->add($alldata);
            $this->success('注册成功！请登录完善个人信息~','User/Login');
        }
        else {
            return $this->fetch();
        }
    }

    public function Personal(){
        return $this->fetch();
    }

    public function Information(){
        if (request()->isPost()) {
            $data =input('post.');
            $data['status'] = 1;
            model('User')->updateByUId($data,$data['uid']);
            $this->success('修改成功!');
        }
       else{
           return $this->fetch();
       }

    }

    public function EmailCheck(){
        return $this->fetch();
    }


    public function RetrievePsw(){
            return $this->fetch();
    }


    public function ChangePswRet(){
        if (request()->isPost()){
            $password = input('post.')['password'];
            $user = model('User')->getUserByUsername(session('we_R_Psw', '', 'rp1')['username']);
            model('User')->updateByUId(['password'=>md5($password)], $user->uid);
            $this->success('修改成功','User/Login');
            session(null, 'rp1');
        }
        else{
            return $this->fetch();
        }

    }

    public function Logout(){
        session(null, 'we');
        $this->redirect(url('Index/index'));
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