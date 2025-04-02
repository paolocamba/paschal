<?php
header('Content-Type: application/json');

// Database connection
$conn = new mysqli("localhost", "root", "", "pascal");

if ($conn->connect_error) {
    die(json_encode(["success" => false, "error" => "Database connection failed."]));
}

// Get POST data
$LoanID = intval($_POST['LoanID']);

// Handle file upload
$uploadDir = '../dist/assets/images/property/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$imagePaths = [null, null, null]; // Assuming three columns for images

if (!empty($_FILES['property_images']['name'][0])) {
    foreach ($_FILES['property_images']['name'] as $key => $name) {
        if ($key >= 3) break; // Limit to 3 images
        
        $filename = uniqid() . '_' . basename($name);
        $targetPath = $uploadDir . $filename;
        
        if (move_uploaded_file($_FILES['property_images']['tmp_name'][$key], $targetPath)) {
            $imagePaths[$key] = 'property/' . $filename;
        }
    }
}

// Prepare data for database
$data = [
    'LoanID' => $LoanID,
    'validator_square_meters' => floatval($_POST['validator_square_meters']),
    'validator_type_of_land' => $_POST['validator_type_of_land'],
    'validator_location' => $_POST['validator_location'],
    'validator_right_of_way' => $_POST['validator_right_of_way'] ?? 'No',
    'validator_hospital' => $_POST['validator_hospital'] ?? 'No',
    'validator_clinic' => $_POST['validator_clinic'] ?? 'No',
    'validator_school' => $_POST['validator_school'] ?? 'No',
    'validator_market' => $_POST['validator_market'] ?? 'No',
    'validator_church' => $_POST['validator_church'] ?? 'No',
    'validator_terminal' => $_POST['validator_terminal'] ?? 'No',
    'image_path1' => $imagePaths[0],
    'image_path2' => $imagePaths[1],
    'image_path3' => $imagePaths[2]
];

// Build the SQL query (adding validated_date)
$query = "INSERT INTO land_appraisal (
    LoanID, validator_square_meters, validator_type_of_land, validator_location, 
    validator_right_of_way, validator_hospital, validator_clinic, validator_school, 
    validator_market, validator_church, validator_terminal, 
    image_path1, image_path2, image_path3, validated_date
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
ON DUPLICATE KEY UPDATE
    validator_square_meters = VALUES(validator_square_meters),
    validator_type_of_land = VALUES(validator_type_of_land),
    validator_location = VALUES(validator_location),
    validator_right_of_way = VALUES(validator_right_of_way),
    validator_hospital = VALUES(validator_hospital),
    validator_clinic = VALUES(validator_clinic),
    validator_school = VALUES(validator_school),
    validator_market = VALUES(validator_market),
    validator_church = VALUES(validator_church),
    validator_terminal = VALUES(validator_terminal),
    image_path1 = VALUES(image_path1),
    image_path2 = VALUES(image_path2),
    image_path3 = VALUES(image_path3),
    validated_date = NOW()";  // ✅ Update validated_date

$stmt = $conn->prepare($query);
$stmt->bind_param("idssssssssssss", 
    $data['LoanID'],
    $data['validator_square_meters'],
    $data['validator_type_of_land'],
    $data['validator_location'],
    $data['validator_right_of_way'],
    $data['validator_hospital'],
    $data['validator_clinic'],
    $data['validator_school'],
    $data['validator_market'],
    $data['validator_church'],
    $data['validator_terminal'],
    $data['image_path1'],
    $data['image_path2'],
    $data['image_path3']
);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
