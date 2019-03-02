<?php
namespace app\Admin\controller;
use think\Controller;
/**
 * Created by PhpStorm.
 * User: times
 * Date: 2018/12/18
 * Time: 17:49
 */
class Base extends Controller{
    public $account = '';
    public function _initialize() {
        // 获取登录用户数据
        $this->assign('user', $this->getLoginUser());

    }

    public function getLoginUser() {
        if(!$this->account) {
            $this->account = session('we_admin', '', 'we');
        }
        return $this->account;
    }
}