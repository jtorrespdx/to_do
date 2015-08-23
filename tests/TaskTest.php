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
    class TaskTest extends PHPUnit_Framework_TestCase
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
            //Make a description and then store it in a new object
            $description = "Do Dishes";
            $test_task = new Task($description);

            //Act
            //See if getDescription is functional
            $result = $test_task->getDescription();

            //Assert
            $this->assertEquals($description, $result);
        }

        function testSetDescription()
        {
            //Arrange
            $description = "Do Dishes";
            $test_task = new Task($description);

            //Act
            //Set the task to a new task to see if the task has changed
            $test_task->setDescription("Drink coffee");
            $result = $test_task->getDescription();

            //Assert
            $this->assertEquals("Drink coffee", $result);
        }

        function testGetId()
        {
            //Arrange
            $id = 1;
            $description = "Wash the dog";
            $test_task = new Task($description, $id);

            //Act
            $result = $test_task->getId();

            //Assert
            $this->assertEquals(1, $result);
        }

        function testSave()
       {
           //Arrange
           $description = "Wash the dog";
           $id = 1;
           $test_task = new Task($description, $id);

           //Act
           $test_task->save();

           //Assert
           $result = Task::getAll();
           $this->assertEquals($test_task, $result[0]);
       }

       function testSaveSetsId()
        {
            //Arrange
            $description = "Wash the dog";
            $id = 1;
            $test_task = new Task($description, $id);

            //Act
            $test_task->save();

            //Assert
            $this->assertEquals(true, is_numeric($test_task->getId()));
        }

        function testGetAll()
        {
            //Arrange
            $description = "Wash the dog";
            $id = 1;
            $test_task = new Task($description, $id);
            $test_task->save();


            $description2 = "Water the lawn";
            $id2 = 2;
            $test_task2 = new Task($description2, $id2);
            $test_task2->save();

            //Act
            $result = Task::getAll();

            //Assert
            $this->assertEquals([$test_task, $test_task2], $result);
        }

        function testDeleteAll()
        {
            //Arrange
            $description = "Wash the dog";
            $id = 1;
            $test_task = new Task($description, $id);
            $test_task->save();

            $description2 = "Water the lawn";
            $id2 = 2;
            $test_task2 = new Task($description2, $id2);
            $test_task2->save();

            //Act
            Task::deleteAll();

            //Assert
            $result = Task::getAll();
            $this->assertEquals([], $result);
        }

        function testFind()
        {
            //Arrange
            $description = "Wash the dog";
            $id = 1;
            $test_task = new Task($description, $id);
            $test_task->save();

            $description2 = "Water the lawn";
            $id2 = 2;
            $test_task2 = new Task($description2, $id2);
            $test_task2->save();

            //Act
            $result = Task::find($test_task->getId());

            //Assert
            $this->assertEquals($test_task, $result);
        }

        function testUpdate()
        {
            //Arrange
            $description = "Wash the dog";
            $id = 1;
            $test_task = new Task($description, $id);
            $test_task->save();

            $new_description = "Clean the dog";

            //Act
            $test_task->update($new_description);

            //Assert
            $this->assertEquals("Clean the dog", $test_task->getDescription());
        }

        function testDeleteTask()
        {
            //Arrange
            $description = "Wash the dog";
            $id = 1;
            $test_task = new Task($description, $id);
            $test_task->save();

            $description2 = "Water the lawn";
            $id2 = 2;
            $test_task2 = new Task($description2, $id2);
            $test_task2->save();


            //Act
            $test_task->delete();

            //Assert
            $this->assertEquals([$test_task2], Task::getAll());
        }
    }
?>
