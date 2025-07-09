<?php
header('Content-Type: application/json');

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
        'hospital' => $input['hospital'] ?? 'No',
        'clinic' => $input['clinic'] ?? 'No',
        'school' => $input['school'] ?? 'No',
        'market' => $input['market'] ?? 'No',
        'church' => $input['church'] ?? 'No',
        'terminal' => $input['terminal'] ?? 'No'
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

    // Decode the response
    $result = json_decode($response, true);
    
    if ($httpCode !== 200) {
        throw new Exception("API returned HTTP code $httpCode");
    }

    if (!isset($result['status']) || $result['status'] !== 'success') {
        $error = $result['message'] ?? 'Unknown error';
        throw new Exception("Prediction failed: $error");
    }

    if (!isset($result['prediction'])) {
        throw new Exception("Missing prediction data in response");
    }

    // Return the prediction results in the expected format
    echo json_encode([
        'success' => true,
        'prediction' => [
            'final_zonal_value' => $result['prediction']['final_zonal_value'] ?? 0,
            'EMV_per_sqm' => $result['prediction']['EMV_per_sqm'] ?? 0,
            'total_value' => $result['prediction']['total_value'] ?? 0,
            'loanable_amount' => $result['prediction']['loanable_amount'] ?? 0
        ]
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}