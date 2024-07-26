<?php
// Database components and connection
$host = "localhost";
$username = "root";
$password = "";
$dbname = "php_crud";

$con = mysqli_connect($host, $username, $password, $dbname);

$response = array();

if ($con) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Check if required fields are set
        if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone_no'])) {
            // Insert data into the database
            $name = $_POST['name'];
            $email = $_POST['email'];
            $phone_no = $_POST['phone_no'];


            // // Directory name based on customer's name (or any other unique identifier)
            // $directory_name = 'customers/' . preg_replace('/[^a-zA-Z0-9_-]/', '', $name);

            // // Create the directory
            // if (!file_exists($directory_name)) {
            //     mkdir($directory_name, 0777, true);
            //     $response["message"] = "Data inserted successfully and directory created.";

            // Using prepared statements to prevent SQL injection
            $query = "INSERT INTO customer (name, email, phone_no) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($con, $query);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "sss", $name, $email, $phone_no);

                if (mysqli_stmt_execute($stmt)) {
                    $response["message"] = "Data inserted successfully.";
                } else {
                    $response["error"] = "Failed to insert data.";
                }

                mysqli_stmt_close($stmt);
            } else {
                $response["error"] = "Failed to prepare the query.";
            }
        } else {
            $response["error"] = "Required fields are missing.";
        }
    } else {
        $response["error"] = "Invalid request method.";
    }

    mysqli_close($con);
} else {
    $response["error"] = "Database connection failed.";
}

echo json_encode($response, JSON_PRETTY_PRINT);
?>


