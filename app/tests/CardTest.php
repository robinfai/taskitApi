<?php
/**
 * Created by PhpStorm.
 * @author RobinFai <robinfai9@gmail.com>
 * @link http://bootstrap.robinfai.com
 * @license http://www.yiiframework.com/license/
 * @version $Id: CardListTest.php 2013-12-21 09:32 robin.fai $
 */

class CardTest extends TestCase{

    public static $isTruncate = false;

    public function setUp() {
        parent::setUp();

        $user = User::all()->first();
        //Auth::login($user);
        $this->be($user);

        Board::boot();
        if (!self::$isTruncate) {
            self::$isTruncate = true;
            DB::table('cards')->delete();
        }
    }

    /**
     * 测试创建Card
     * @dataProvider CardDataProvider
     */
    public function testCreate($title){
        $board = Board::all()->first();
        $cardList = $board->cardLists->first();
        $data = array('title'=>$title,'card_list_id'=>$cardList->id);
        $this->client->request('POST','/card/create',$data);
        $card = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertTrue($data['title'] == $card['title']);
    }

    /**
     * 测试更新Card
     * @depends testCreate
     * @dataProvider CardDataProvider
     */
    public function testUpdate($title){
        $card = Card::where('title','=',$title)->first();

        $data = array('title'=>$title.'-update','id'=>$card->id);
        $this->client->request('POST','/card/update/'.$card->id,$data);
        $board = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertTrue($data['title'] == $board['title']);
    }


    /**
     * 测试Card添加颜色
     * @depends testUpdate
     * @dataProvider CardColorProvider
     */
    public function testAddColor($color){
        $list = Card::all();
        foreach($list as $card){
            /* @var $card Card*/
            $this->client->request('GET',"/card/addColor/{$card->id}/{$color}");
            $this->assertTrue(json_decode($this->client->getResponse()->getContent(), true));
        }
    }

    /**
     * 测试Card移除颜色
     * @depends testUpdate
     * @dataProvider CardColorProvider
     */
    public function testRemoveColor($color){
        $list = Card::all();
        foreach($list as $card){
            /* @var $card Card*/
            $this->client->request('GET',"/card/removeColor/{$card->id}/{$color}");
            $result = json_decode($this->client->getResponse()->getContent(), true);
            $this->assertTrue($result);
        }
    }

    /**
     * 测试Card设置完成时间
     * @depends testUpdate
     * @dataProvider CardColorProvider
     */
    public function testSetCompletionTime(){
        $list = Card::all();
        foreach($list as $card){
            /* @var $card Card*/
            $dateTime = date('Y-m-d H:i:s',time() + rand(1,86400));
            $this->client->request('POST',"/card/setCompletionTime/{$card->id}",array('completion_time'=>$dateTime));
            $result = $this->client->getResponse()->getContent();
            Log::error($result);
            $result = json_decode($result, true);
            $this->assertTrue($result);
            $card = Card::find($card->id);
            $this->assertTrue($card->completion_time === $dateTime);
        }
    }


    /**
     * Card
     */
    public function testGet(){
        $cardList = Auth::user()->boards->first()->cardLists->first();
        $this->client->request('GET','/card/getList/'.$cardList->id);
        $list = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertTrue(is_array($list));
    }

    /**
     * 测试Card添加成员
     * @depends testUpdate
     * @dataProvider CardDataProvider
     */
    public function testAddMember($title){
        $card = Card::where('title','=',$title.'-update')->first();
        $member = User::all()->last();
        $data = array('user_id'=>$member->id);
        $this->client->request('POST','/card/addMember/'.$card->id,$data);
        $this->assertResponseOk();
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertTrue($data['user_id'] == $response['user_id']);
        $this->client->request('POST','/card/addMember/'.$card->id,$data);
        $this->assertResponseOk();
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertTrue(count($response['card_id'])===1);
    }

    /**
     * 测试Card移除成员
     * @depends testAddMember
     * @dataProvider CardDataProvider
     */
    public function testRemoveMember($title){
        $card = Card::where('title','=',$title.'-update')->first();
        $member = User::all()->last();
        $data = array('user_id'=>$member->id);
        $this->client->request('POST','/card/removeMember/'.$card->id,$data);
        $this->assertResponseOk();
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertTrue($response);
        $this->client->request('POST','/card/removeMember/'.$card->id,$data);
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertTrue(!$response);

        $member = User::all()->first();
        $data = array('user_id'=>$member->id);
        $this->client->request('POST','/card/removeMember/'.$card->id,$data);
        $this->assertResponseOk();
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertTrue($response['status']==false);
    }

    /**
     * Card测试数据提供器
     * @return array
     */
    public function CardDataProvider() {
        $data = array();
        for ($i = 0; $i < 2; $i++) {
            $title = 'title' . $i;
            $data[] = array('title' => $title);
        }
        return $data;
    }

    /**
     * CardColor测试数据提供器
     * @return array
     */
    public function CardColorProvider() {
        return array(array('green'),array('yellow'),array('orange'),array('red'),array('purple'),array('blue'));
    }
} 