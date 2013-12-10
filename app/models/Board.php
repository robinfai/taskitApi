<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 13-12-10
 * Time: 下午5:50
 */

/**
 * Class Board
 *
 * @property string $title 标题
 * @property int $creator_id 创建人ID
 * @property string $email 邮箱
 */
class Board extends Model{
    public function creator(){
        return $this->belongsTo('user','creator_id','id');
    }

    public function members(){
        return $this->belongsToMany('User','board_members','board_id','user_id');
    }
} 