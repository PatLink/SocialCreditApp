<?php

class ProjectEditingTest extends TestCase {
    public function __construct(){
    $this->mock = Mockery::mock('Eloquent', 'Projects');
    }
    public function tearDown()
    {
        Mockery::close();
    }
}
