<?php

class UserTest extends TestCase {

    public static $isTruncate = false;

    public function setUp() {
        parent::setUp();
        User::boot();
        if (!self::$isTruncate) {
            self::$isTruncate = true;
            DB::table('users')->delete();
        }
    }

    /**
     * @dataProvider UserDataProvider
     */
    public function testRegister($username, $password, $email) {
        $userData = array('username' => $username, 'password' => $password, 'email' => $email);
        $this->client->request('POST', '/register', $userData);
        $id = $this->client->getResponse()->getContent();
        $this->client->request('GET', '/user/' . $id);
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        
        $this->assertTrue($responseData['username'] == $userData['username']);
    }

    /**
     * @dataProvider UserDataProvider
     */
    public function testLogin($username, $password, $email) {
        $userData = array('username' => $username, 'password' => $password, 'email' => $email);
        $this->client->request('POST', '/login', $userData);
        $id = $this->client->getResponse()->getContent();
        $this->client->request('GET', '/user/' . $id);
        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertTrue($responseData['username'] == $userData['username']);
    }

    /**
     * 测试用户数据库
     * @return array
     */
    public function UserDataProvider() {
        $data = array();
        for ($i = 0; $i < 10; $i++) {
            $username = 'username' . $i;
            $data[] = array('username' => $username, 'password' => $username, 'email' => $username . '@robinfai.com');
        }
        return $data;
    }

}
