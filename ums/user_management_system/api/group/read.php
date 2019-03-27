<?php
/**
 * @category     User Management System
 * @author       Prashant
 * @createdOn    27 Mar 2019
 * @description  Get group data
 */

// required header
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/group.php';
 
// instantiate database and group object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$group = new Group($db);
 
// query groups
$stmt = $group->readAll();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    // products array
    $groups_arr=array();
    $groups_arr["records"]=array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $group_item=array(
            "id" => $id,
            "name" => $name,
            "total_user" => $total_user,
            "description" => html_entity_decode($description)
        );
 
        array_push($groups_arr["records"], $group_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show groups data in json format
    echo json_encode($groups_arr);
}
 
else{
 
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no groups found
    echo json_encode(
        array("message" => "No group found.")
    );
}
?>