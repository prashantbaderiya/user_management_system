<?php
/**
 * @category     User Management System
 * @author       Prashant
 * @createdOn    27 Mar 2019
 * @description  Update Group
 */

$arrayPost = array();
$arrayPost["id"] = $_POST['id'];
$arrayPost["name"] = $_POST['name'];
$arrayPost["description"] = $_POST['description'];



$options = array(
  'http' => array(
    'method'  => 'POST',
    'content' => json_encode( $arrayPost ),
    'header'=>  "Content-Type: application/json\r\n" .
                "Accept: application/json\r\n"
    )
);

$context  = stream_context_create( $options );
$result = file_get_contents("http://".$_SERVER['SERVER_NAME']. "/user_management_system/api/group/update.php" , false, $context );
$response = json_decode($result, true);

header('Location: http://'.$_SERVER['HTTP_HOST']. '/user_management_system/view/groups.php');

