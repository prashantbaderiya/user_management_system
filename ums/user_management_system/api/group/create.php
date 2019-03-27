<?php
/**
 * @category     User Management System
 * @author       Prashant
 * @createdOn    27 Mar 2019
 * @description  Create a group
 */

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// get database connection
include_once '../config/database.php';
 
// instantiate group object
include_once '../objects/group.php';
 
$database = new Database();
$db = $database->getConnection();
 
$group = new Group($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(
    !empty($data->name) &&
    !empty($data->description)
){
 
    // set group property values
    $group->name = $data->name;
    $group->description = $data->description;
    $group->created = date('Y-m-d H:i:s');

    // create the group
    if($group->create()){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the group
        echo json_encode(array("message" => "Group was created."));
    }
 
    // if unable to create the group, tell the group
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the group
        echo json_encode(array("message" => "Unable to create group."));
    }
}
 
// tell the group data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the group
    echo json_encode(array("message" => "Unable to create group. Data is incomplete."));
}
?>