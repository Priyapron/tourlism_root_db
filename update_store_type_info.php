<?php
include 'conn.php';
header("Access-Control-Allow-Origin: *");

$xcase = $_POST['case'];

// Common variables
$store_type_code = mysqli_real_escape_string($conn, $_POST['store_type_code']);
$store_type_name = mysqli_real_escape_string($conn, $_POST['store_type_name']);

$response = array();

switch ($xcase) {
    case '1': // insert
        $sql = "INSERT INTO store_type (store_type_code, store_type_name)
                VALUES (?, ?)";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ss', $store_type_code, $store_type_name);

        if (mysqli_stmt_execute($stmt)) {
            $response['status'] = 200;
            $response['message'] = "Store type data inserted successfully";
        } else {
            $response['status'] = 500;
            $response['message'] = "Failed to insert store type data: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
        break;

    case '2': // update
        $sql = "UPDATE store_type
                SET store_type_name=?
                WHERE store_type_code=?";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ss', $store_type_name, $store_type_code);

        if (mysqli_stmt_execute($stmt)) {
            $response['status'] = 200;
            $response['message'] = "Store type data updated successfully";
        } else {
            $response['status'] = 500;
            $response['message'] = "Failed to update store type data: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
        break;

    case '3': // delete
        $sql = "DELETE FROM store_type WHERE store_type_code=?";
        
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 's', $store_type_code);
        
        if (mysqli_stmt_execute($stmt)) {
            $response['status'] = 200;
            $response['message'] = "Store type data deleted successfully";
        } else {
            $response['status'] = 500;
            $response['message'] = "Failed to delete store type data: " . mysqli_error($conn);
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
