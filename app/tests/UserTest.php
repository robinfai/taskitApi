<?php

class UserTest extends TestCase {

    public static $isTruncate = false;

    public function setUp() {
        parent::setUp();
        if (!self::$isTruncate) {
            self::$isTruncate = true;
            DB::table('users')->delete();
        }
    }

    /**
     * @dataProvider UserDataProvider
     */
    public function testCreateUser($username, $password, $email) {
        $userData = array('username' => $username, 'password' => $password, 'email' => $email);
        $this->client->request('POST', '/register', $userData);
        $id = $this->client->getResponse()->getContent();
        $this->client->request('GET', '/user/' . $id);
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        
        $this->assertTrue($responseData['username'] == $userData['username']);
    }

    public function UserDataProvider() {
        $data = array();
        for ($i = 0; $i < 1; $i++) {
            $username = 'user' . $i;
            $data[] = array('username' => $username, 'password' => $username, 'email' => $username . '@robinfai.com');
        }
        return $data;
    }

}
