<?php
include 'conn.php';
header("Access-Control-Allow-Origin: *");

$xcase = $_POST['case'];

// Common variables
$store_code = mysqli_real_escape_string($conn, $_POST['store_code']);
$store_name = mysqli_real_escape_string($conn, $_POST['store_name']);
$store_type_code = mysqli_real_escape_string($conn, $_POST['store_type_code']);

$response = array();

// Check if 'case' key is set in the POST request
if (isset($_POST['case'])) {
    $xcase = $_POST['case'];

    // Check the value of 'case' key
    switch ($xcase) {
        case '1': // insert
            // Check if other expected keys are set
            if (isset($_POST['store_name'], $_POST['store_type_code'])) {
                // Calculate the new store_code based on existing data
                $sql ="SELECT MAX(store_code) AS MAX_STORE_CODE FROM store_name ";
                $objQuery = mysqli_query($conn, $sql) or die(mysqli_error($conn));

                $store_code = 1; // Default value
                while ($row1 = mysqli_fetch_array($objQuery)) {
                    if ($row1["MAX_STORE_CODE"] != "") {
                        $store_code = $row1["MAX_STORE_CODE"] + 1;
                    }
                }

                $store_name = mysqli_real_escape_string($conn, $_POST['store_name']);
                $store_type_code = mysqli_real_escape_string($conn, $_POST['store_type_code']);

                $sql = "INSERT INTO store_name (store_code, store_name, store_type_code)
                        VALUES (?, ?, ?)";

                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, 'iss', $store_code, $store_name, $store_type_code);

                if (mysqli_stmt_execute($stmt)) {
                    $response['status'] = 200;
                    $response['message'] = "Store data inserted successfully";
                } else {
                    $response['status'] = 500;
                    $response['message'] = "Failed to insert store data: " . mysqli_error($conn);
                }

                mysqli_stmt_close($stmt);
            } else {
                $response['status'] = 400;
                $response['message'] = "Missing required parameters for case 1";
            }
            break;

    case '2': // update
        $sql = "UPDATE store_name
                SET store_name=?, store_type_code=?
                WHERE store_code=?";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'sss', $store_name, $store_type_code, $store_code);

        if (mysqli_stmt_execute($stmt)) {
            $response['status'] = 200;
            $response['message'] = "Store data updated successfully";
        } else {
            $response['status'] = 500;
            $response['message'] = "Failed to update store data: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
        break;

        case '3': // delete
           
                $sql = "DELETE FROM store_name WHERE store_code=?";
                
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, 's', $store_code);
                
                if (mysqli_stmt_execute($stmt)) {
                    $response['status'] = 200;
                    $response['message'] = "Store data deleted successfully";
                } else {
                    $response['status'] = 500;
                    $response['message'] = "Failed to delete store data: " . mysqli_error($conn);
                }
                
                mysqli_stmt_close($stmt);
           
            
            break;        
        
}

} else {
    $response['status'] = 400;
    $response['message'] = "'case' key is missing in the POST request";
    }

echo json_encode($response);

mysqli_close($conn);
?>