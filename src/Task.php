<?php

    class Task
    {
        //Properties
        private $description;
        private $id;

        //Constructor
        function __construct($description, $id = null)
        {
            $this->description = $description;
            $this->id = $id;
        }

        //Description getter and setter
        function getDescription()
        {
            return $this->description;
        }

        function setDescription($new_description)
        {
            $this->description = $new_description;
        }

        //Id getter (No setter MySQL handles that)
        function getId()
        {
            return $this->id;
        }

        //Save function
        function save()
        {
            //Call upon PDO to insert the task description into our DB
            $GLOBALS['DB']->exec("INSERT INTO tasks (description) Values ('{$this->getDescription()} ');");

            //Lets our app store ID as a genrated ID from MySQL
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        //getAll static function
        static function getAll()
        {
            //First pull all tasks from the DB
            $returned_tasks = $GLOBALS['DB']->query("SELECT * FROM tasks;");

            //Get the information from the DB and store them as new task objects
            $tasks = array();
            foreach($returned_tasks as $task)
            {
                $description = $task['description'];
                $id = $task['id'];
                $new_task = new TASK($description, $id);
                array_push($tasks, $new_task);
            }

            //Now send back the array of newley made task objects from the DB
            return $tasks;
        }

        //delete all
        static function deleteAll()
        {
            //Tell PDO to execute a wipe from tasks
            $GLOBALS['DB']->exec("DELETE FROM tasks");
        }

        //Find by ID
        static function find($search_id)
        {
            //Set up a null property
            $found_task = null;

            //Acquire all of our task row information
            $tasks = TASK::getAll();

            //Sift through tasks and pull out the one matching our id
            foreach($tasks as $task)
            {
                $task_id = $task->getId();

                if($task_id == $search_id)
                {
                    $found_task = $task;
                }
            }

            //Return the found task
            return $found_task;
        }

        //Update description
        function update($new_description)
        {
            //Place our new description variable inside where our old description used to be. locating by current id. use {} to place php commands
            $GLOBALS['DB']->exec("UPDATE tasks SET description = '{new_description}' WHERE id = {$this->getId()};");

            //now change the current loaded instance to the new description.
            $this->setDescription($new_description);
        }

        //Delete single task
        function delete()
        {
            //Delete this current instance of task by location of id in DB
            $GLOBALS['DB']->exec("DELETE FROM tasks WHERE id = {$this->getId()}");
        }
    }
?>
