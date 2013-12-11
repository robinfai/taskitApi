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

    public function setUp() {
        parent::setUp();

        $user = User::all()->first();
        Auth::login($user);

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
    }

    /**
     * Board测试数据提供器
     * @return array
     */
    public function BoardDataProvider() {
        $data = array();
        for ($i = 0; $i < 10; $i++) {
            $title = 'title' . $i;
            $data[] = array('title' => $title);
        }
        return $data;
    }
} 