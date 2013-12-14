<?php
/**
 * Created by PhpStorm.
 * @author RobinFai <robinfai9@gmail.com>
 * @link http://bootstrap.robinfai.com
 * @license http://www.yiiframework.com/license/
 * @version $Id: EventFlow.php 2013-12-14 09:04 robin.fai $
 */

/**
 * Class EventFlow
 * 事件流
 * @property int $id
 * @property int $board_id
 * @property int $user_id
 * @property string $event
 */
class EventFlow extends Model{

    /**
     * 关系定义，从属于User，获取
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo('User');
    }
} 