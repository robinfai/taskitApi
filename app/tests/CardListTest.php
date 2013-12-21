<?php
/**
 * Created by PhpStorm.
 * @author RobinFai <robinfai9@gmail.com>
 * @link http://bootstrap.robinfai.com
 * @license http://www.yiiframework.com/license/
 * @version $Id: CardListTest.php 2013-12-21 09:32 robin.fai $
 */

class CardListTest extends TestCase{

    public static $isTruncate = false;

    public function setUp() {
        parent::setUp();

        $user = User::all()->first();
        //Auth::login($user);
        $this->be($user);

        Board::boot();
        if (!self::$isTruncate) {
            self::$isTruncate = true;
            DB::table('card_lists')->delete();
        }
    }

    /**
     * 测试创建Board
     * @dataProvider CardListDataProvider
     */
    public function testCreate($title){
        $board = Board::all()->first();
        $data = array('title'=>$title,'board_id'=>$board->id);
        $this->client->request('POST','/cardList/create',$data);
        $board = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertTrue($data['title'] == $board['title']);
        return $board;
    }

    /**
     * Board测试数据提供器
     * @return array
     */
    public function CardListDataProvider() {
        $data = array();
        for ($i = 0; $i < 2; $i++) {
            $title = 'title' . $i;
            $data[] = array('title' => $title);
        }
        return $data;
    }
} 