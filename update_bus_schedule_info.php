<?php
include 'conn.php';
header("Access-Control-Allow-Origin: *");

$xcase = $_POST['case'];

// Common variables
$sequence_number = mysqli_real_escape_string($conn, $_POST['sequence_number']);
$time = mysqli_real_escape_string($conn, $_POST['time']);
$location_code = mysqli_real_escape_string($conn, $_POST['location_code']);

$response = array();

switch ($xcase) {
    case '1': // insert
        // Function to get the next sequence number
        $get_next_sequence_number = function () use ($conn) {
            $sql = "SELECT MAX(sequence_number) AS MAX_SEQUENCE_NUMBER FROM bus_schedule";
            $objQuery = mysqli_query($conn, $sql) or die(mysqli_error($conn));

            $sequence_number = 1; // Default value
            while ($row = mysqli_fetch_array($objQuery)) {
                if ($row["MAX_SEQUENCE_NUMBER"] !== null) {
                    $sequence_number = $row["MAX_SEQUENCE_NUMBER"] + 1;
                }
            }

            return $sequence_number;
        };

        $store_code = $get_next_sequence_number(); // Get the next sequence number

        $sql = "INSERT INTO bus_schedule (sequence_number, time, location_code)
                VALUES (?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'sss', $store_code, $time, $location_code);

        if (mysqli_stmt_execute($stmt)) {
            $response['status'] = 200;
            $response['message'] = "Bus schedule data inserted successfully";
            $response['sequence_number'] = $store_code;
        } else {
            $response['status'] = 500;
            $response['message'] = "Failed to insert bus schedule data: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
        break;

    case '2': // update
        $sql = "UPDATE bus_schedule
                SET time=?, location_code=?
                WHERE sequence_number=?";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'sss', $time, $location_code, $sequence_number);

        if (mysqli_stmt_execute($stmt)) {
            $response['status'] = 200;
            $response['message'] = "Bus schedule data updated successfully";
        } else {
            $response['status'] = 500;
            $response['message'] = "Failed to update bus schedule data: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
        break;

    case '3': // delete
        $sql = "DELETE FROM bus_schedule WHERE sequence_number=?";
        
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 's', $sequence_number);
        
        if (mysqli_stmt_execute($stmt)) {
            $response['status'] = 200;
            $response['message'] = "Bus schedule data deleted successfully";
        } else {
            $response['status'] = 500;
            $response['message'] = "Failed to delete bus schedule data: " . mysqli_error($conn);
        }
        
        mysqli_stmt_close($stmt);
        break;

    default:
        $response['status'] = 400;
        $response['message'] = "Invalid case provided";
        break;
}

echo json_encode($response);

mysqli_close($conn);
?>
