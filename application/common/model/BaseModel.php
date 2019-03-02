<?php
/**
 * basemodel 公共的model层
 */
namespace app\common\model;

use think\Model;
/**
 * Created by PhpStorm.
 * User: times
 * Date: 2018/12/17
 * Time: 18:10
 */
class BaseModel extends Model
{
    protected  $autoWriteTimestamp = true;
    public function add($data) {
        $this->save($data);
        return $this->id;
    }

    public function updateById($data, $id) {
        return $this->allowField(true)->save($data, ['admin_id'=>$id]);
    }
    public function updateByUId($data, $uid){
        return $this->allowField(true)->save($data, ['uid'=>$uid]);
    }

    public function Select($data){
        return $this->where($data)->select();
    }
}