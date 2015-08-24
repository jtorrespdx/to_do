<?php

    class Category
    {
        //Make properties
        private $name;
        private $id;

        //Constructor
        function __construct($name, $id = null)
        {
            $this->name = $name;
            $this->id = $id;
        }

        //Name getter and setter
        function getName()
        {
            return $this->name;
        }

        function setName($new_name)
        {
            $this->name = (string) $new_name;
        }

        //ID getter
        function getId()
        {
            return $this->id;
        }

        //Save function
        function save()
        {
            //Call upon DB PDO to store the name of the category in a new column
            $GLOBALS['DB']->exec ("INSERT INTO categories (name) VALUES ('{$this->getName()}');");

            //And to have PDO make MySQl assign it an id? Further studies must be conducted.
            $this->id = $GLOBALS['DB']->lastInsertId();


        }

        //Update function
        function update($new_name)
        {
            //Call upon DB PDO to update this ID locations name to new name
            $GLOBALS['DB']->exec("UPDATE categories SET name = '{$new_name}' WHERE id = {$this->getId()};");

            //Set current session name to updated name
            $this->setName($new_name);
        }

        //Static function getAll
        static function getAll()
        {
            //Query the DB to grab all categories and store them in an array.
            $returned_categories = $GLOBALS['DB']->query("SELECT * FROM categories;");

            //Run through the DB query and form each row into a category object.
            $categories = array();
            foreach ($returned_categories as $category)
            {
                $name = $category['name'];
                $id = $category['id'];
                $new_category = new Category($name, $id);
                array_push($categories, $new_category);
            }
            //Send out the packedged MySQL query for PHP to manipulate
            return $categories;
        }

        //DeleteAll function
        static function deleteAll()
        {
            //Call upon DB PDO to execute MySQL command to wipe everything from categories
            $GLOBALS['DB']->exec("DELETE FROM categories;");
        }

        //Fin function
        static function find($search_id)
        {
            //Set a null variable for found category before functions process.
            $found_category = null;

            //Use getAll function to query and store data for php to process
            $categories = Category::getAll();

            //go through categories and extract the one with the id we are searching for.
            foreach($categories as $category)
            {
                $category_id = $category->getId();
                if($category_id == $search_id)
                {
                    $found_category = $category;
                }
            }
            return $found_category;
        }

        //addTask Method
        function addTask($task)
        {
            //Calld upon the DB PDO to execute a command to place this current ID and a tasks ID to the join table. This will associate them together.
            $GLOBALS['DB']->exec("INSERT INTO categories_tasks (category_id, task_id) VALUES ({$this->getId()}, {$task->getId()});");
        }

        function getTasks()
        {
            //We make a query that pulls from the join table the task ids that are connected to this category ID
            $query = $GLOBALS['DB']->query("SELECT task_id FROM categories_tasks WHERE category_id = {$this->getId()};");

            //We then turn them into a PDO object that we then parse out all of the task id's using the fetchAll method.
            $task_ids = $query->fetchAll(PDO::FETCH_ASSOC);

            //Create an array to hold all of the new task objects
            $tasks = array();

            //Make a foreach loop to cylce through each task id from our join table. (This foreach loop is cycling a nested array) *fetchAll returns a nested array*
            foreach($task_ids as $id)
            {
                //Extract the current id from the join table data (Wich is a an array) and place it in variable
                $task_id = $id['task_id'];

                //Call the DB PDO to query and extract all the tasks with the same ID as that of the join table.
                $result = $GLOBALS['DB']->query("SELECT * FROM tasks WHERE id = {$task_id};");

                //Extract all results as a nested array (fetchAll results are always nested arrays)
                $returned_task = $result->fetchAll(PDO::FETCH_ASSOC);

                //Since returned task is a nested array we go to the first array and grab the data in the key description. and store that description in a variable.
                $description = $returned_task[0]['description'];

                //Same thing as before but with the id
                $id = $returned_task[0]['id'];

                $completed = $returned_task[0]['completed'];

                $due_date = $returned_task[0]['due_date'];
                //We then make objects out of them shove those objects in an array and send them out.
                $new_task = new Task($description, $id, $completed, $due_date);
                array_push($tasks, $new_task);
            }

            return $tasks;

        }

        function delete()
        {
            //Now includes many-to-many support by deleting from the database this category and deleting every row in the database associated with it.
            $GLOBALS['DB']->exec("DELETE FROM categories WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM categories_tasks WHERE category_id = {$this->getId()};");
        }


    }

?>
