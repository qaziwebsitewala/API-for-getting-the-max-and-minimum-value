<?php

// Dummy data (prices in $)
$data = [50, 60, 70];

// Dummy username and password for basic authentication
$valid_username = 'admin';
$valid_password = 'Welcome90#@!';

// Check if the request contains the proper authentication credentials
function authenticate() {
    if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
        return false;
    }

    global $valid_username, $valid_password;
    $username = $_SERVER['PHP_AUTH_USER'];
    $password = $_SERVER['PHP_AUTH_PW'];

    return ($username === $valid_username && $password === $valid_password);
}

// Define the API endpoint
if ($_SERVER['REQUEST_METHOD'] === 'GET' && parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) === '/dummy_api.php/api/min_max_values') {
    // Authenticate the user
    if (!authenticate()) {
        header('WWW-Authenticate: Basic realm="API Authentication"');
        http_response_code(401);
        echo json_encode(["message" => "Authentication required"]);
        exit;
    }

    $minValue = min($data);
    $maxValue = max($data);

    // Add dollar sign to the values
    $minValueWithDollar = '$' . $minValue;
    $maxValueWithDollar = '$' . $maxValue;

    $result = [
        "min_Price" => $minValueWithDollar,
        "max_Price" => $maxValueWithDollar
    ];

    // Set the response headers
    header('Content-Type: application/json');
    http_response_code(200);

    // Output the JSON response
    echo json_encode($result);
} else {
    // Endpoint not found
    http_response_code(404);
    echo json_encode(["message" => "Endpoint not found"]);
}
