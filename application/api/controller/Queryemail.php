<?php
namespace app\api\controller;
use think\Controller;
class Queryemail extends Controller
{
    private  $obj;
    public function _initialize() {
        $this->obj = model("Admin");
    }
    public function getEmailByUserName() {
        $username = input('username');
        if(!$username) {
            $this->error('username不合法');
        }

        $info = $this->obj->getAdminByAdminname($username);
        $email = $info['email'];
        if(!$email) {
            return show(0,'error','');
        }
            return show(1,'success', $email);
    }

    public function getEmailByUserName2() {
        $username = input('username');
        if(!$username) {
            $this->error('username不合法');
        }

        $info = model('User')->getUserByUsername($username);
        $email = $info['email'];
        if(!$email) {
            return show(0,'error','');
        }
        return show(1,'success', $email);
    }
}