<?php
/**
 * @category     User Management System
 * @author       Prashant
 * @createdOn    27 Mar 2019
 * @description  Delete Group
 */

$arrayPost = array();
$arrayPost["id"] = $_GET['id'];



$options = array(
  'http' => array(
    'method'  => 'POST',
    'content' => json_encode( $arrayPost ),
    'header'=>  "Content-Type: application/json\r\n" .
                "Accept: application/json\r\n"
    )
);

$context  = stream_context_create( $options );
$result = file_get_contents("http://".$_SERVER['SERVER_NAME']. "/user_management_system/api/group/delete.php" , false, $context );
$response = json_decode($result, true);

header('Location: http://'.$_SERVER['HTTP_HOST']. '/user_management_system/view/groups.php');

