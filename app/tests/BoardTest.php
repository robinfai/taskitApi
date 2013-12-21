<?php
/**
 * Created by PhpStorm.
 * @author RobinFai <robinfai9@gmail.com>
 * @link http://bootstrap.robinfai.com
 * @license http://www.yiiframework.com/license/
 * @version $Id: BoardTest.php 2013-12-11 20:36 robin.fai $
 */

class BoardTest extends TestCase{

    public static $isTruncate = false;

    public $list;

    public function setUp() {
        parent::setUp();

        $user = User::all()->first();
        //Auth::login($user);
        $this->be($user);

        Board::boot();
        if (!self::$isTruncate) {
            self::$isTruncate = true;
            DB::table('boards')->delete();
        }
    }

    /**
     * 测试创建Board
     * @dataProvider BoardDataProvider
     */
    public function testCreate($title){

        $data = array('title'=>$title);
        $this->client->request('POST','/board/create',$data);
        $board = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertTrue($data['title'] == $board['title']);
        return $board;
    }

    /**
     * 测试更新Board
     * @depends testCreate
     * @dataProvider BoardDataProvider
     */
    public function testUpdate($title){
        $board = Board::where('title','=',$title)->first();
        $data = array('title'=>$title.'-update');
        $this->client->request('POST','/board/update/'.$board->id,$data);
        $board = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertTrue($data['title'] == $board['title']);
    }

    /**
     * 测试Board添加成员
     * @depends testUpdate
     * @dataProvider BoardDataProvider
     */
    public function testAddMember($title){
        $board = Board::where('title','=',$title.'-update')->first();
        $member = User::all()->last();
        $data = array('user_id'=>$member->id);
        $this->client->request('POST','/board/addMember/'.$board->id,$data);
        $this->assertResponseOk();
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertTrue($data['user_id'] == $response['user_id']);
        $this->client->request('POST','/board/addMember/'.$board->id,$data);
        $this->assertResponseOk();
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertTrue(count($response['board_id'])===1);
    }

    /**
     * 测试Board修改成员为管理员
     * @depends testAddMember
     * @dataProvider BoardDataProvider
     */
    public function testAddAdmin($title){
        //测试普通用户添加为管理员
        $board = Board::where('title','=',$title.'-update')->first();
        $member = User::all()->last();
        $data = array('user_id'=>$member->id);
        $this->client->request('POST','/board/addAdmin/'.$board->id,$data);
        $this->assertResponseOk();
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertTrue($response);

        //测试用户已经是管理员时,再次修改为管理员
        $this->client->request('POST','/board/addAdmin/'.$board->id,$data);
        $this->assertResponseOk();
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertTrue($response['status']==false);

        //测试不存在的用户或者个成员添加为管理员时的反馈
        $data = array('user_id'=>0);
        $this->client->request('POST','/board/addAdmin/'.$board->id,$data);
        $this->assertResponseStatus(404);
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertTrue(!$response);
    }

    /**
     * 测试Board移除管理员
     * @depends testAddMember
     * @dataProvider BoardDataProvider
     */
    public function testRemoveAdmin($title){
        //测试移除管理员
        $board = Board::where('title','=',$title.'-update')->first();
        $member = User::all()->last();
        $data = array('user_id'=>$member->id);
        $this->client->request('POST','/board/removeAdmin/'.$board->id,$data);
        $this->assertResponseOk();
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertTrue($response);

        //测试将普通成员进行移除管理员时的反馈
        $this->client->request('POST','/board/removeAdmin/'.$board->id,$data);
        $this->assertResponseOk();
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertTrue($response['status']==false);

        //测试不存在的用户或者个成员移除管理员时的反馈
        $data = array('user_id'=>0);
        $this->client->request('POST','/board/removeAdmin/'.$board->id,$data);
        $this->assertResponseStatus(404);
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertTrue(!$response);
    }

    /**
     * 测试Board移除成员
     * @depends testRemoveAdmin
     * @dataProvider BoardDataProvider
     */
    public function testRemoveMember($title){
        $board = Board::where('title','=',$title.'-update')->first();
        $member = User::all()->last();
        $data = array('user_id'=>$member->id);
        $this->client->request('POST','/board/removeMember/'.$board->id,$data);
        $this->assertResponseOk();
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertTrue($response);
        $this->client->request('POST','/board/removeMember/'.$board->id,$data);
        $this->assertResponseStatus(404);
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertTrue(!$response);

        $member = User::all()->first();
        $data = array('user_id'=>$member->id);
        $this->client->request('POST','/board/removeMember/'.$board->id,$data);
        $this->assertResponseOk();
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertTrue($response['status']==false);
    }

    /**
     * Board测试数据提供器
     * @return array
     */
    public function BoardDataProvider() {
        $data = array();
        for ($i = 0; $i < 2; $i++) {
            $title = 'title' . $i;
            $data[] = array('title' => $title);
        }
        return $data;
    }

    public function testGetList(){
        $this->client->request('POST','/board');
        $list = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertTrue(is_array($list));
    }
} 