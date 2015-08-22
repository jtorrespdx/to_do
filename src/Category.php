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

    }

?>
