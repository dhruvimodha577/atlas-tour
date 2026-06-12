<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Include database connection
require_once "../config/database.php";

// Get raw posted data
$data = json_decode(file_get_contents("php://input"), true);

// Fallback to $_POST if json_decode is empty
$full_name = "";
$email = "";
$phone = "";
$password = "";

if (isset($data['full_name']) && isset($data['email']) && isset($data['phone']) && isset($data['password'])) {
    $full_name = trim($data['full_name']);
    $email = trim($data['email']);
    $phone = trim($data['phone']);
    $password = trim($data['password']);
} elseif (isset($_POST['full_name']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['password'])) {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);
}

if (empty($full_name) || empty($email) || empty($phone) || empty($password)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Please fill all required fields."]);
    exit();
}

try {
    // Check if email already exists
    $check_query = "SELECT id FROM users WHERE email = ?";
    $check_stmt = mysqli_prepare($conn, $check_query);
    
    if ($check_stmt) {
        mysqli_stmt_bind_param($check_stmt, "s", $email);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);
        
        if (mysqli_num_rows($check_result) > 0) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "Email already exists! Please Login."]);
            exit();
        }
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Database check error."]);
        exit();
    }

    // Insert user into database
    $insert_query = "INSERT INTO users (full_name, email, phone, password) VALUES (?, ?, ?, ?)";
    $insert_stmt = mysqli_prepare($conn, $insert_query);
    
    if ($insert_stmt) {
        mysqli_stmt_bind_param($insert_stmt, "ssss", $full_name, $email, $phone, $password);
        if (mysqli_stmt_execute($insert_stmt)) {
            echo json_encode(["success" => true, "message" => "Registration Successful!"]);
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Failed to register user: " . mysqli_stmt_error($insert_stmt)]);
        }
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Database insertion error."]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "An error occurred: " . $e->getMessage()]);
}
?>
