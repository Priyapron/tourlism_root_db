<?php
include 'conn.php';
header("Access-Control-Allow-Origin: *");

// Query to get distinct location_code from tourist_location
$sql = "SELECT DISTINCT location_code FROM tourist_location";

$result = mysqli_query($conn, $sql);

$response = array();

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $response[] = array('location_code' => $row['location_code']);
    }
}

echo json_encode($response);

mysqli_close($conn);
?>
