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

    /**
     * 卡片添加颜色
     * @param $color
     * @return bool|string
     */
    public function addColor($color){
        $cardColor = new CardColor();
        $cardColor->card_id = $this->id;
        $cardColor->color=$color;

        if ($cardColor->validate() && $cardColor->save()) {
            $this->cardList->board->addEventFlow("对[{$this->title}]卡片添加了颜色：{$color}");
            return $cardColor->toJson();
        } else {
            $this->addError('color',$cardColor->getErrors());
            return false;
        }
    }


    /**
     * 移除卡片颜色
     * @param $color
     * @return bool
     */
    public function removeColor($color){
        /* @var $cardColor CardColor */
        if (CardColor::where('card_id','=',$this->id)->where('color','=',$color)->delete()) {
            $this->cardList->board->addEventFlow("对[{$this->title}]卡片移除了颜色：{$color}");
            return true;
        } else {
            return false;
        }
    }

    /**
     * 卡片拥有颜色
     * @param $color
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|static
     */
    public function hasColor($color){
        return !!$this->getColor($color);
    }

    /**
     * 获取卡片颜色
     * @param string $color
     * @return CardColor
     */
    public function getColor($color){
        $color = CardColor::where('card_id','=',$this->id)->where('color','=',$color)->first();
        return $color;
    }
} 