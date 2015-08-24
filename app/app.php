<?php

    //Link to sources and autoload
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Task.php";
    require_once __DIR__."/../src/Category.php";

    //Declare a new silex application
    $app = new Silex\Application();

    //connect the app to the database
    $server = 'mysql:host=localhost8889;dbname=to_do';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));

    //Twig links
    $app->get("/", function() use ($app) {
    return $app['twig']->render('index.twig', array('categories' => Category::getAll()), 'tasks' => Task::getAll());
    });

    $app->get("/tasks", function() use ($app) {
    return $app['twig']->render('tasks.twig', array('tasks' => Task::getAll()));
    });

    $app->get("/categories", function() use ($app) {
    return $app['twig']->render('categories.twig', array('categories' => Category::getAll()));
    });

    $app->post("/tasks", function() use ($app) {
    $description = $_POST['description'];
    $task = new Task($description);
    $task->save();
    return $app['twig']->render('tasks.twig', array('tasks' => Task::getAll()));
    });

    $app->get("/tasks/{id}", function($id) use ($app) {
    $task = Task::find($id);
    return $app['twig']->render('task.twig', array('task' => $task, 'categories' => $task->categories(), 'all_categories' => Category::getAll()));
    });

    $app->post("/categories", function() use ($app) {
    $category = new Category($_POST['name']);
    $category->save();
    return $app['twig']->render('categories.twig', array('categories' => Category::getAll()));
    });

    $app->get("/categories/{id}", function($id) use ($app) {
    $category = Category::find($id);
    return $app['twig']->render('category.twig', array('category' => $category, 'tasks' => $category->tasks(), 'all_tasks' => Task::getAll()));
    });


?>
