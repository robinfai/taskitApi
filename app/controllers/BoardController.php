<?php
/**
 * Created by PhpStorm.
 * @author RobinFai <robinfai9@gmail.com>
 * @link http://bootstrap.robinfai.com
 * @license http://www.yiiframework.com/license/
 * @version $Id: BoardController.php 2013-12-11 20:45 robin.fai $
 */

class BoardController extends BaseController{

    /**
     * 创建board
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $board = new Board();
        $board->title = Input::get('title');
        $user = Auth::user();
        $board->creator_id = $user->id;

        $validator = $board->getValidator();
        if ($validator->fails()) {
            $messages = $validator->messages();
            return Response::make($messages, 200);
        }

        if ($board->save()) {
            $board->addEventFlow('创建了这个面板');
            $boardMember = new BoardMember();
            $boardMember->board_id = $board->id;
            $boardMember->user_id = $user->id;
            $boardMember->is_admin = 1;
            $boardMember->save();
            return Response::make($board->toJson(), 200);
        } else {
            return Response::make($board->getErrors(), 404);
        }
    }

    /**
     * 更新boards
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $board = Board::findOrFail($id);
        /* @var $board Board */
        $board->title = Input::get('title');

        if(!$board->member(Auth::user()->id)){
            return Response::make('user not board member', 200);
        }

        $validator = $board->getValidator();
        if ($validator->fails()) {
            $messages = $validator->messages();
            return Response::make($messages, 200);
        }

        if ($board->save()) {
            $originalTitle = $board->getOriginal('title');
            $board->addEventFlow("重命名面板标题(原标题:{$originalTitle})");
            return Response::make($board->toJson(), 200);
        } else {
            return Response::make($board->getErrors(), 404);
        }
    }

    /**
     * 获取board列表
     */
    public function index(){
        return Response::make(Auth::user()->boards->toJson(), 200);
    }
} 