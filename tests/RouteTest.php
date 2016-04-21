<?php

class RouteTest extends TestCase {

	public function testPublicRoutes()
	{
		/* $response = $this->call('GET', '/'); */
		/* $this->assertEquals(200, $response->getStatusCode()); */
		$response = $this->call('GET', '/about');
		$this->assertEquals(200, $response->getStatusCode());
		$response = $this->call('GET', '/privacy');
		$this->assertEquals(200, $response->getStatusCode());
		$response = $this->call('GET', '/impress');
		$this->assertEquals(200, $response->getStatusCode());
		$response = $this->call('GET', '/auth/login');
		$this->assertEquals(200, $response->getStatusCode());
	}


    public function testPrivateRoutes(){
        foreach($this->devUsers as $email){
            Auth::attempt(array('email' => $email, 'password' => $this->password));
            $this->call('GET', '/auth/login');
            $this->assertRedirectedTo('/dashboard');

            $this->call('GET', '/');
            $this->assertRedirectedTo('/user');

            $response = $this->call('GET', '/user');
            $this->assertEquals(200, $response->getStatusCode());
            Auth::logout();
        }
    }

    public function testProfessor(){
        Auth::attempt(array('email' => $this->devUsers[2], 'password' => 'weristdas'));
        $this->get('/user');
        $this->assertViewHasAll(['projects','userprojects','unconfirmed_projects','applyed_projects']);
    }
    public function testLecturer(){
        Auth::attempt(array('email' => $this->devUsers[1], 'password' => 'weristdas'));
        $this->get('/user');
        $this->assertViewHasAll(['projects','userprojects']);
        $this->assertViewMissing('unconfirmed_projects');
        $this->assertViewMissing('applyed_projects');
    }

    public function testStudent(){
        Auth::attempt(array('email' => $this->devUsers[0], 'password' => 'weristdas'));
        $this->get('/user');
        $this->assertViewHasAll(['projects','userprojects']);
        $this->assertViewMissing('unconfirmed_projects');
        $this->assertViewMissing('applyed_projects');

    }

}
