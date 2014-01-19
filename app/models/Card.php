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
 * @property string $description 描述
 * @property int $creator_id 创建人ID
 * @property int $card_list_id 卡片列表ID
 * @property CardList $cardList 看板
 */
class Card extends Model{

    /**
     * 关系定义，从属于User，获取
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator(){
        return $this->belongsTo('user','creator_id','id');
    }

    /**
     * 关系定义，从属于CardList，获取
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cardList(){
        return $this->belongsTo('cardList','card_list_id','id');
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
            'card_list_id'=>'required|integer'
        ));
    }
} 