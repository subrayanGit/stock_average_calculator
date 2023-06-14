<?php
session_start();
require_once('lang.php');
extract($_POST);
require_once('db.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');
if($type == 'get_data'){
    $sql = "SELECT * FROM `tbl_questions` order by id asc";
    $result = $conn->query($sql);
    $total = 0;
	$response = array();
    while($row = $result->fetch_assoc()) {
        $response['questions'][] = $row;
    }
	$sql = "SELECT * FROM `tbl_constituency` order by id asc";	
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {
        $response['constituency'][$row['tamil_district']][] = array($row['tamil_constituency'], $row['tamil_loksaba']);
    }	
	$sql = "select count(*) as count from tbl_users";
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {
        $response['count'] = $row['count'];
    }	
	$response['translation'] = $translate;
    echo json_encode($response);
    exit;
}

if($type == 'insert_data'){
	$details = json_decode($data);
    $stmt = $conn->prepare("INSERT INTO `tbl_users`( `name`, `age`, `constituency`, `loksaba`, `answer1`, `answer2`, `answer3`, `answer4`, `answer5`, `answer6`, `answer7`, `answer8`, `answer9`, `answer10`, `answer11`, `answer12`, `answer13`, `created`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $ip = get_client_ip();
    $date = date('Y-m-d');
	$name = '';
    $stmt->bind_param("ssssssssssssssssss", $name, $details[0], $details[1], $details[2], $details[3], $details[4], $details[5], $details[6], $details[7], $details[8], $details[9], $details[10], $details[11], $details[12], $details[13], $details[14], $name, $date);
    $stmt->execute();
    $stmt->close(); 
	$_SESSION['is_completed'] = true;	
	echo json_encode($_SESSION['is_completed']);
    exit;
}
// Function to get the client IP address
function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
?>