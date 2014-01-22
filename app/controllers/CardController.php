<?php
/**
 * Created by PhpStorm.
 * @author RobinFai <robinfai9@gmail.com>
 * @link http://bootstrap.robinfai.com
 * @license http://www.yiiframework.com/license/
 * @version $Id: CardListController.php 2013-12-21 09:57 robin.fai $
 */

class CardController extends BaseController{

    /**
     * 创建card
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $card = new Card();
        $card->title = Input::get('title');
        $card->card_list_id = Input::get('card_list_id');
        $user = Auth::user();
        $card->creator_id = $user->id;

        $validator = $card->getValidator();
        if ($validator->fails()) {
            $messages = $validator->messages();
            return Response::make($messages, 200);
        }

        if ($card->save()) {
            $card->cardList->board->addEventFlow('创建了这个卡片');
            return Response::make($card->toJson(), 200);
        } else {
            return Response::make($card->getErrors(), 404);
        }
    }

    /**
     * 更新card
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $card = Card::findOrFail($id);
        /* @var $card Card */
        $card->title = Input::get('title');

        if(!$card->cardList->board->member(Auth::user()->id)){
            return Response::make('user not board member', 200);
        }

        $validator = $card->getValidator();
        if ($validator->fails()) {
            $messages = $validator->messages();
            return Response::make($messages, 200);
        }

        if ($card->save()) {
            $originalTitle = $card->getOriginal('title');
            $card->cardList->board->addEventFlow("重命卡片标题(原标题:{$originalTitle})");
            return Response::make($card->toJson(), 200);
        } else {
            return Response::make($card->getErrors(), 404);
        }
    }

    /**
     * card列表
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function index($id){
        $cardList = CardList::findOrFail($id);
        /* @var $cardList CardList*/
        if($cardList->board->member(Auth::user()->id)){
            return Response::make($cardList->cards->toJson(), 200);
        }else{
            return Response::make('card not found', 404);
        }

    }
} 