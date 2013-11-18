<?php

class UserTest extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testCreateUser()
	{
        $userData = array('username'=>'testUser'.time(),'password'=>'password1','email'=>'dd@dd.cc');
		$this->client->request('GET', '/user/create',$userData);
//        $response = $this->call('get', '/user/');
//        $this->assertTrue($this->client->getResponse()->isOk());
        $id = $this->client->getResponse()->getContent();
        $this->client->request('GET', '/user/'.$id);
        $responseData = json_decode($this->client->getResponse()->getContent(),true);

        $this->assertTrue($responseData['username']==$userData['username']);

	}

}