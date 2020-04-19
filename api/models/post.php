<?php

$dbconn = null;
if(getenv('DATABASE_URL')){
    $connectionConfig = parse_url(getenv('DATABASE_URL'));
    $host = $connectionConfig['host'];
    $user = $connectionConfig['user'];
    $password = $connectionConfig['pass'];
    $port = $connectionConfig['port'];
    $dbname = trim($connectionConfig['path'],'/');
    $dbconn = pg_connect(
        "host=".$host." ".
        "user=".$user." ".
        "password=".$password." ".
        "port=".$port." ".
        "dbname=".$dbname
    );
} else {
    $dbconn = pg_connect("host=localhost dbname=cheese");
}

class Post {
  public $id;
  public $name;
  public $location;
  public $milk;

  public function __construct($id, $name, $location, $milk){
    $this->id = $id;
    $this->name = $name;
    $this->location = $location;
    $this->milk = $milk;
  }
}

class Posts {
  static function all(){
    $posts = array();

    $results = pg_query("SELECT * FROM cheese");

    $row_object = pg_fetch_object($results);
    while($row_object){
      $new_post = new Post(
        intval($row_object->id),
        $row_object->name,
        $row_object->location,
        $row_object->milk
      );
      $posts[] = $new_post;
      $row_object = pg_fetch_object($results);
    }
    return $posts;
  }

  static function create($post){
    $query = "INSERT INTO cheese (name, location, milk) VALUES ($1, $2, $3)";
    $query_params = array($post->name, $post->location, $post->milk);
    pg_query_params($query, $query_params);
    return self::all();
  }

  static function update($updated_post){
      $query = "UPDATE cheese SET name = $1, location = $2, milk = $3 WHERE id = $4";
      $query_params = array($updated_post->name, $updated_post->location, $updated_post->milk, $updated_post->id);
      $result = pg_query_params($query, $query_params);

      return self::all();
    }
    static function delete($id){
      $query = "DELETE FROM cheese WHERE id = $1";
      $query_params = array($id);
      $result = pg_query_params($query, $query_params);

      return self::all();
    }
}

?>
