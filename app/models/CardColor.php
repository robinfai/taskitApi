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
 * @property int $card_id
 * @property string $color 颜色
 * @property Card $card
 */
class CardColor extends Model{

    /**
     * 关系定义，从属于CardList，获取
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function card(){
        return $this->belongsTo('card','card_id','id');
    }

    /**
     * 验证规则
     * @return \Illuminate\Validation\Validator
     */
    public function getValidator() {
        return Validator::make(
            $this->getAttributes(), array(
            'card_id' => 'required|integer',
            'color'=>'required',
        ));
    }
} 