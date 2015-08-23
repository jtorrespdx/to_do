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
            $GLOBALS['DB']->exec("INSERT INTO tasks (description) Values ('{$this->getDescription()}');");

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

        function addCategory($category)
        {
            //Takes the category object argument and places its ID in the join table attached to this tasks id. i.e (This instance object of task if we want to add a category to it will be bound by their Id's in the join table.)
            $GLOBALS['DB']->exec("INSERT INTO categories_tasks (category_id, task_id) VALUES ({$category->getId()}, {$this->getId()});");
        }

        function getCategories()
        {
            //Gets categories associated with this task instance.

            //Make a query object of PDO. Get all ID's of the categories that are associated with this Task instance.
            $query = $GLOBALS['DB']->query("SELECT category_id FROM categories_tasks WHERE task_id = {$this->getId()};");

            //Sift through the database information and store it in a variable as a nested array.
            $category_ids = $query->fetchAll(PDO::FETCH_ASSOC);

            //Make an array for final data transfer
            $categories = array();

            //After the sifted database information is converted to something PHP can play with. (nested array) go through each array in the array.
            foreach($category_ids as $id)
            {
                //Inside the arrays we are going through they should have two values *dbArray['task_id' => INT, 'category_id' => INT]* since we want to get the categories associated with this instance of task we want the category ID's and we want to place them in a variable to manipulate
                $category_id = $id['category_id'];

                //Now that we have the ID of this particular category that is associated with this task. we go back into the database and look for the category thats associated with this category ID and store this information.
                $result = $GLOBALS['DB']->query("SELECT * FROM categories WHERE id = {$category_id};");

                //We now sift through that information by making another nested array by using the PDO object method fetchAll with a PDO static method called FETCH_ASSOC (I am assuming that FETCH_ASSOC stands for fetch associated). and we then store theses arrays in said variable.
                $returned_category = $result->fetchAll(PDO::FETCH_ASSOC);

                //We then take the nested array and  grab the information and place them in variables. After extracting that information we then make a category object and place the information within
                $name = $returned_category[0]['name'];
                $id = $returned_category[0]['id'];
                $new_category = new Category($name, $id);

                //At the end of this loop cycle we push our new category object into the the categories array. and then continuen the foreach loop over again.
                array_push($categories, $new_category);
            }
            //We then return all categories associated with this task.
            return $categories;
        }
    }
?>
