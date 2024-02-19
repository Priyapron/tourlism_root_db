<?php
include 'conn.php';
header("Access-Control-Allow-Origin: *");

$response = array();

// Check if 'case' key is set in the POST request
if (isset($_POST['case'])) {
    $xcase = $_POST['case'];

    // Check the value of 'case' key
    switch ($xcase) {
        case '1': // insert
            // Check if other expected keys are set
            if (isset($_POST['location_name'], $_POST['location_details'], $_POST['latitude'], $_POST['longitude'])) {
                // Calculate the new location_code based on existing data
                $sql ="SELECT MAX(location_code) AS MAX_LOCATION_CODE FROM tourist_location ";
                $objQuery = mysqli_query($conn, $sql) or die(mysqli_error($conn));

                $location_code = 1; // Default value
                while ($row1 = mysqli_fetch_array($objQuery)) {
                    if ($row1["MAX_LOCATION_CODE"] != "") {
                        $location_code = $row1["MAX_LOCATION_CODE"] + 1;
                    }
                }

                $location_name = mysqli_real_escape_string($conn, $_POST['location_name']);
                $location_details = mysqli_real_escape_string($conn, $_POST['location_details']);
                $latitude = mysqli_real_escape_string($conn, $_POST['latitude']);
                $longitude = mysqli_real_escape_string($conn, $_POST['longitude']);

                $sql = "INSERT INTO tourist_location (location_code, location_name, location_details, latitude, longitude)
                        VALUES (?, ?, ?, ?, ?)";

                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, 'issss', $location_code, $location_name, $location_details, $latitude, $longitude);

                if (mysqli_stmt_execute($stmt)) {
                    $response['status'] = 200;
                    $response['message'] = "Tourist location data inserted successfully";
                } else {
                    $response['status'] = 500;
                    $response['message'] = "Failed to insert tourist location data: " . mysqli_error($conn);
                }

                mysqli_stmt_close($stmt);
            } else {
                $response['status'] = 400;
                $response['message'] = "Missing required parameters for case 1";
            }
            break;

        case '2': // update
            // Check if other expected keys are set
            if (isset($_POST['location_name'], $_POST['location_details'], $_POST['latitude'], $_POST['longitude'], $_POST['location_code'])) {
                $location_name = mysqli_real_escape_string($conn, $_POST['location_name']);
                $location_details = mysqli_real_escape_string($conn, $_POST['location_details']);
                $latitude = mysqli_real_escape_string($conn, $_POST['latitude']);
                $longitude = mysqli_real_escape_string($conn, $_POST['longitude']);
                $location_code = mysqli_real_escape_string($conn, $_POST['location_code']);

                $sql = "UPDATE tourist_location
                        SET location_name=?, location_details=?, latitude=?, longitude=?
                        WHERE location_code=?";

                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, 'ssssi', $location_name, $location_details, $latitude, $longitude, $location_code);

                if (mysqli_stmt_execute($stmt)) {
                    $response['status'] = 200;
                    $response['message'] = "Tourist location data updated successfully";
                } else {
                    $response['status'] = 500;
                    $response['message'] = "Failed to update tourist location data: " . mysqli_error($conn);
                }

                mysqli_stmt_close($stmt);
            } else {
                $response['status'] = 400;
                $response['message'] = "Missing required parameters for case 2";
            }
            break;

        // Add case 3 for delete operation if needed
        case '3': // delete
            if (isset($_POST['location_code'])) {
                $location_code = mysqli_real_escape_string($conn, $_POST['location_code']);

                $sql = "DELETE FROM tourist_location WHERE location_code=?";
                
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, 'i', $location_code);
                
                if (mysqli_stmt_execute($stmt)) {
                    $response['status'] = 200;
                    $response['message'] = "Tourist location data deleted successfully";
                } else {
                    $response['status'] = 500;
                    $response['message'] = "Failed to delete tourist location data: " . mysqli_error($conn);
                }
                
                mysqli_stmt_close($stmt);
            } else {
                $response['status'] = 400;
                $response['message'] = "Missing required parameters for case 3";
            }
            break;

        default:
            $response['status'] = 400;
            $response['message'] = "Invalid case provided";
            break;
    }
} else {
    $response['status'] = 400;
    $response['message'] = "'case' key is missing in the POST request";
}

echo json_encode($response);

mysqli_close($conn);
?>
