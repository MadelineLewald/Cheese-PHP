<?php
include_once __DIR__ . '/../models/post.php';
header('Content-Type: application/json');

if ($_REQUEST['action'] === 'index') {
  echo "index";
  echo json_encode(Posts::all());
} 
elseif ($_REQUEST['action'] === 'post') {
  $request_milk = file_get_contents('php://input');
  $milk_object = json_decode($request_milk);
  $new_post = new Post(null, $milk_object->name, $milk_object->location, $milk_object->milk);
  $all_posts = Posts::create($new_post);
  echo json_encode($all_posts);
} else if ($_REQUEST['action'] === 'update'){
  $request_milk = file_get_contents('php://input');
  $milk_object = json_decode($request_milk);
  $updated_post = new Post($_REQUEST['id'], $milk_object->name, $milk_object->location, $milk_object->milk);
  $all_posts = Posts::update($updated_post);
  echo json_encode($all_posts);
  } else if ($_REQUEST['action'] === 'delete') {
    $all_posts = Posts::delete($_REQUEST['id']);
    echo json_encode($all_posts);
  }

 ?>
