<?php
/**
 * Created by PhpStorm.
 * @author RobinFai <robinfai9@gmail.com>
 * @link http://bootstrap.robinfai.com
 * @license http://www.yiiframework.com/license/
 * @version $Id: CardList.php 2013-12-21 09:58 robin.fai $
 */

/**
 * Class CardList
 * @property int $id
 * @property string $title 标题
 * @property int $creator_id 创建人ID
 * @property int $board_id 看板ID
 * @property Board $board 看板
 */
class CardList extends Model{

    /**
     * 关系定义，从属于User，获取
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator(){
        return $this->belongsTo('user','creator_id','id');
    }

    /**
     * 关系定义，从属于Board，获取
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function board(){
        return $this->belongsTo('board','board_id','id');
    }

    /**
     * 验证规则
     * @return \Illuminate\Validation\Validator
     */
    public function getValidator() {
        return Validator::make(
            $this->getAttributes(), array(
            'title' => 'required',
            'creator_id'=>'required|integer',
            'board_id'=>'required|integer'
        ));
    }
} 