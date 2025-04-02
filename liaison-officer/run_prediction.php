
<?php
header('Content-Type: application/json');

$inputJSON = file_get_contents('php://input');
error_log("Raw Input JSON: " . $inputJSON);

$input = json_decode($inputJSON, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    error_log("JSON Decode Error: " . json_last_error_msg());
    echo json_encode(['success' => false, 'error' => 'Invalid JSON input: ' . json_last_error_msg()]);
    exit;
}



try {
    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON input: ' . json_last_error_msg());
    }

    // Validate required fields
    $required = ['LoanID', 'square_meters', 'type_of_land', 'location_name'];
    foreach ($required as $field) {
        if (empty($input[$field])) {
            throw new Exception("Missing required field: $field");
        }
    }

    // Prepare data for Flask API
    $data = [
        'loan_id' => (int)$input['LoanID'],
        'square_meters' => (float)$input['square_meters'],
        'type_of_land' => $input['type_of_land'],
        'location' => $input['location_name'],
        'right_of_way' => $input['right_of_way'] ?? 'No',
        'hospital' => $input['has_hospital'] ?? 'No',
        'clinic' => $input['has_clinic'] ?? 'No',
        'school' => $input['has_school'] ?? 'No',
        'market' => $input['has_market'] ?? 'No',
        'church' => $input['has_church'] ?? 'No',
        'terminal' => $input['has_terminal'] ?? 'No'
    ];

    // Call Flask API
    $ch = curl_init('http://localhost:5000/predict');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_TIMEOUT => 30
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if (curl_errno($ch)) {
        throw new Exception('API connection error: ' . curl_error($ch));
    }

    curl_close($ch);

    // Log the response for debugging
    error_log("API Response: " . $response);
    
    $result = json_decode($response, true);
    
    if ($httpCode !== 200) {
        throw new Exception("API returned HTTP code $httpCode. Response: " . $response);
    }

    if (!isset($result['success']) || !$result['success']) {
        throw new Exception("Prediction failed: " . json_encode($result));

    }

    // Return the prediction results
    echo json_encode([
        'success' => true,
        'prediction' => $result['prediction']
    ]);

} catch (Exception $e) {
    error_log("Error in run_prediction.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}