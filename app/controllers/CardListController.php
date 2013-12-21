<?php
/**
 * Created by PhpStorm.
 * @author RobinFai <robinfai9@gmail.com>
 * @link http://bootstrap.robinfai.com
 * @license http://www.yiiframework.com/license/
 * @version $Id: CardListController.php 2013-12-21 09:57 robin.fai $
 */

class CardListController extends BaseController{

    /**
     * 创建card list
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $cardList = new CardList();
        $cardList->title = Input::get('title');
        $cardList->board_id = Input::get('board_id');
        $user = Auth::user();
        $cardList->creator_id = $user->id;

        $validator = $cardList->getValidator();
        if ($validator->fails()) {
            $messages = $validator->messages();
            return Response::make($messages, 200);
        }

        if ($cardList->save()) {
            $cardList->board->addEventFlow('创建了这个卡片列表');
            return Response::make($cardList->toJson(), 200);
        } else {
            return Response::make($cardList->getErrors(), 404);
        }
    }
} 