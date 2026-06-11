<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Include database connection
require_once "../config/database.php";

// Get raw posted data
$data = json_decode(file_get_contents("php://input"), true);

// Fallback to $_POST if json_decode is empty
$email = "";
$password = "";

if (isset($data['email']) && isset($data['password'])) {
    $email = trim($data['email']);
    $password = trim($data['password']);
} elseif (isset($_POST['email']) && isset($_POST['password'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
}

if (empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Please enter both email and password."]);
    exit();
}

try {
    // Check if user exists
    $query = "SELECT id, full_name, password FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            
            // Note: users database stores password in plain text.
            if ($password === $user['password']) {
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];
                
                echo json_encode([
                    "success" => true,
                    "message" => "Login successful!",
                    "user" => [
                        "id" => $user['id'],
                        "full_name" => $user['full_name']
                    ]
                ]);
                exit();
            }
        }
        
        // Either email not found or password didn't match
        http_response_code(401);
        echo json_encode(["success" => false, "message" => "Invalid Email or Password!"]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Database query error."]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "An error occurred: " . $e->getMessage()]);
}
?>
