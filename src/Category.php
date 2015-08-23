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

        //Delete function
        function delete()
        {
            //Call upon DB PDO to execute a MySQL command that deletes the column from categories where this id resides.
            $GLOBALS['DB']->exec("DELETE FROM categories WHERE id = {$this->getId()};");
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

        //Task Method
        function addTask()
        {
            //Calld upon the DB PDO to execute a command to place this current ID and a tasks ID to the join table. This will associate them together.
            $GLOBALS['DB']->exec("INSERT INTO categories_tasks (category_id, task_id) VALUES ({$this->getId()}, {$task->getId()});");
        }
    }

?>
