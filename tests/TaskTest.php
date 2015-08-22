<?php

    //Not sure why this is needed further study must be conducted
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    //Routing to classes
    require_once "src/Task.php";
    require_once "src/Category.php";

    //connect to DB
    $server = 'mysql:host=localhost:8889;dbname=to_do_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    //Our testing class using phpunit
    class TaskTest extends PHUnit_Framework_TestCase
    {
        //Our teardown so we start with a fresh DB everytime we test.
        protected function tearDown()
        {
            Task::deleteAll();
            Category::deleteAll();
        }

        //Test our get description
        function testGetDescription()
        {
            //Arrange
            $description = "Do Dishes";
            $test_task = new Task($description);

            //Act
            $result = $test_task->getDescription();

            //Assert
            $this->assertEquals($description, $result);
        }
    }
?>
