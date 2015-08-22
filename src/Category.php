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
            //Call upon DB PDO to store the name of the category where this current objects id is located
            $GLOBALS['DB']->exec("UPDATE categories SET name = '{$new_name}' WHERE id = {$this->getId()};");

            //Set current session name to updated name
            $this->setName($new_name);
        }

        //Update function
        

    }

?>
