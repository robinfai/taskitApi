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
 * @property int $id
 * @property string $title 标题
 * @property int $creator_id 创建人ID
 */
class Board extends Model{

    /**
     * 关系定义，从属于User，获取
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator(){
        return $this->belongsTo('user','creator_id','id');
    }

    /**
     * 关系定义，拥有多个User，获取所有的成员
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function members(){
        return $this->hasMany('BoardMember','board_id','id');
    }

    /**
     * 关系定义，拥有多个cardList，获取所有的卡片列表
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cardLists(){
        return $this->hasMany('CardList','board_id','id');
    }

    /**
     * 关系定义，拥有多个User，获取所有的管理员
     * @return mixed
     */
    public function admin(){
        return $this->members()->where('is_admin','=','1');
    }

    /**
     * 关系定义,拥有多个事件流,获取所有的事件流模型
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function eventFlows(){
        return $this->hasMany('EventFlow');
    }

    /**
     * 根据ID获取成员模型
     * @param int $id
     * @return mixed
     */
    public function member($id){
        return $this->members()->where('user_id','=',$id)->first();
    }

    /**
     * 验证规则
     * @return \Illuminate\Validation\Validator
     */
    public function getValidator() {
        return Validator::make(
            $this->getAttributes(), array(
            'title' => 'required',
            'creator_id'=>'required|integer'
        ));
    }

    /**
     * 添加事件流
     * @param $event
     * @return EventFlow
     */
    public function addEventFlow($event){
        $eventFlow = new EventFlow();
        $eventFlow->board_id = $this->id;
        $eventFlow->user_id = Auth::user()->id;
        $eventFlow->event = $event;
        $eventFlow->save();
        return $eventFlow;
    }
} 