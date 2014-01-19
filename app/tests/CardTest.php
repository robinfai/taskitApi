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
     * 测试创建Board
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
     * 测试更新Board
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
     * Card
     */
    public function testGet(){
        $cardList = Auth::user()->boards->first()->cardLists->first();
        $this->client->request('GET','/card/getList/'.$cardList->id);
        $list = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertTrue(is_array($list));
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
} 