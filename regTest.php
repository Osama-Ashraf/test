<?php

// Include database connection or configuration file
// Replace the following values with your actual database credentials
$servername = "DESKTOP-S0MLPOE";
$username = "DESKTOP-S0MLPOE\LENOVO";
$database = "apiTest";

$conn = new mysqli($servername, $username, "", $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    // Check if the action is register
    if (isset($_POST["action"]) && $_POST["action"] === "register") {
        registerUser();
    }
    
    // Check if the action is login
    elseif (isset($_POST["action"]) && $_POST["action"] === "login") {
        loginUser();
    }
}

function registerUser() {
    global $conn;

    // Retrieve POST data
    $data = json_decode(file_get_contents("php://input"));

    // Check if all required fields are provided
    if (isset($data->username) && isset($data->password)) {
        // Sanitize input data
        $username = mysqli_real_escape_string($conn, $data->username);
        $password = mysqli_real_escape_string($conn, $data->password);

        // Hash the password (use a more secure hashing method in production)
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert user data into the database
        $sql = "INSERT INTO users (username, password) VALUES ('$username', '$hashedPassword')";

        if ($conn->query($sql) === TRUE) {
            $response = array("success" => true, "message" => "User registered successfully");
        } else {
            $response = array("success" => false, "message" => "Error: " . $sql . "<br>" . $conn->error);
        }
    } else {
        $response = array("success" => false, "message" => "Missing required fields");
    }

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
}

function loginUser() {
    global $conn;

    // Retrieve POST data
    $data = json_decode(file_get_contents("php://input"));

    // Check if all required fields are provided
    if (isset($data->username) && isset($data->password)) {
        // Sanitize input data
        $username = mysqli_real_escape_string($conn, $data->username);
        $password = mysqli_real_escape_string($conn, $data->password);

        // Retrieve user data from the database
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Verify the password
            if (password_verify($password, $row["password"])) {
                $response = array("success" => true, "message" => "Login successful");
            } else {
                $response = array("success" => false, "message" => "Incorrect password");
            }
        } else {
            $response = array("success" => false, "message" => "User not found");
        }
    } else {
        $response = array("success" => false, "message" => "Missing required fields");
    }

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
}

// Close the database connection
$conn->close();

?>


<?php
// Establish database connection
$servername = "";
$username = "DESKTOP-S0MLPOE\LENOVO";
$database = "test";

$conn = new mysqli($servername, $username, "", $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve POST data
$data = json_decode(file_get_contents("php://input"));

// Check if all required fields are provided
if (isset($data->username) && isset($data->email) && isset($data->password)) {
    // Sanitize input data
    $username = mysqli_real_escape_string($conn, $data->username);
    $email = mysqli_real_escape_string($conn, $data->email);
    $password = mysqli_real_escape_string($conn, $data->password);

    // Hash the password (use a more secure hashing method in a production environment)
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user data into the database
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";

    if ($conn->query($sql) === TRUE) {
        $response = array("success" => true, "message" => "User registered successfully");
    } else {
        $response = array("success" => false, "message" => "Error: " . $sql . "<br>" . $conn->error);
    }
} else {
    $response = array("success" => false, "message" => "Missing required fields");
}

// Close the database connection
$conn->close();

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
