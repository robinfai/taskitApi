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

    /**
     * 添加成员
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function addMember($id)
    {
        $board = Board::findOrFail($id);
        /* @var $board Board */
        $BoardMember = new BoardMember();
        $BoardMember->board_id = $id;
        $BoardMember->user_id = Input::get('user_id');

        $validator = $BoardMember->getValidator();
        if ($validator->fails()) {
            $messages = $validator->messages();
            return Response::make($messages, 200);
        }

        if($BoardMember->save()){
            $board->addEventFlow("添加成员".$BoardMember->user->username);
            return Response::make($BoardMember->toJson());
        }else{
            return Response::make($BoardMember->getErrors(), 200);
        }
    }

    /**
     * 移除成员
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function removeMember($id){
        $board = Board::findOrFail($id);
        /* @var $board Board */
        $BoardMember = $board->member(Input::get('user_id'));
        /* @var $BoardMember BoardMember */
        if($BoardMember){
            if($BoardMember->is_admin){
                return Response::make(json_encode(array('status'=>false,'message'=>'移除管理员失败,请先修改用户权限')));
            }else{
                $BoardMember->delete();
                //BoardMember::where('board_id', '=', $BoardMember->board_id)->where('user_id', '=', $BoardMember->user_id)->delete();
                $board->addEventFlow("移除成员".$BoardMember->user->username);
                return Response::make(json_encode(true));
            }
        }else{
            return Response::make(json_encode(false),404);
        }
    }

    /**
     * 修改成员为管理员
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function addAdmin($id){
        $board = Board::findOrFail($id);
        /* @var $board Board */
        $BoardMember = $board->member(Input::get('user_id'));
        /* @var $BoardMember BoardMember */
        if($BoardMember){
            if($BoardMember->is_admin){
                return Response::make(json_encode(array('status'=>false,'message'=>'用户已成为管理员')));
            }else{
                $BoardMember->is_admin = 1;
                $BoardMember->save();
                $board->addEventFlow("修改成员({$BoardMember->user->username})为管理员");
                return Response::make(json_encode(true));
            }
        }else{
            return Response::make(json_encode(false),404);
        }
    }

    /**
     * 移除管理员
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function removeAdmin($id){
        $board = Board::findOrFail($id);
        /* @var $board Board */
        $BoardMember = $board->member(Input::get('user_id'));
        /* @var $BoardMember BoardMember */
        if($BoardMember){
            if(!$BoardMember->is_admin){
                return Response::make(json_encode(array('status'=>false,'message'=>'用户已成为普通成员')));
            }else{
                $BoardMember->is_admin = 0;
                $BoardMember->save();
                $board->addEventFlow("修改管理员({$BoardMember->user->username})为普通成员");
                return Response::make(json_encode(true));
            }
        }else{
            return Response::make(json_encode(false),404);
        }
    }


} 