<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: DELETE');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Category.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate category post object
  $cat = new DBCategory($db);

  // Get raw posted data
  $data = json_decode(file_get_contents("php://input"));

  // Set ID to UPDATE
  $cat->id = $data->id;

  $test = curl_init('http://localhost/api/categories/?id=' . $cat->id);
    curl_setopt($test, CURLOPT_RETURNTRANSFER, true); // Set option to return the response
    $response = curl_exec($test); // Execute the request and store the response
    curl_close($test); // Close the cURL session
    $test2 = array_values(json_decode($response,true));
    if($test2[0] != $cat->id){

      echo json_encode(array(
          'message' => 'No Category Found'
      ));
      exit();
    }

  // Delete Category
  if($cat->delete()) {
    echo json_encode(
      array('id' => $cat->id)
    );
  } 
