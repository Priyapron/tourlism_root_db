<?php
include 'conn.php';
header("Access-Control-Allow-Origin: *");

// Query to get distinct store_type_code from store_name
$sql = "SELECT DISTINCT store_type_code FROM store_name";

$result = mysqli_query($conn, $sql);

$response = array();

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $response[] = array('store_type_code' => $row['store_type_code']);
    }
}

echo json_encode($response);

mysqli_close($conn);
?>
