<?php
/**
 * @category     User Management System
 * @author       Prashant
 * @createdOn    27 Mar 2019
 * @description  Delete group data
 */

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// include database and object file
include_once '../config/database.php';
include_once '../objects/group.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare group object
$group = new Group($db);
 
// get group id
$data = json_decode(file_get_contents("php://input"));
 
// set group id to be deleted
$group->id = $data->id;
 
// delete the group
if($group->delete()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the group
    echo json_encode(array("message" => "Group was deleted."));
}
 
// if unable to delete the group
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the group
    echo json_encode(array("message" => "Unable to delete group."));
}
?>