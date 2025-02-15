<?php
                include '../connection/config.php';

                // Enable error reporting
                ini_set('display_errors', 1);
                error_reporting(E_ALL);

                // Debug session and login status
                if (!isset($_SESSION['user_id'])) {
                    // Redirect to login if no user is logged in
                    header("Location: member-login.php");
                    exit();
                }

                $user_id = $_SESSION['user_id'];

                // Get the loan type from the URL (either 'regular' or 'collateral')
                $loan_type = isset($_GET['loanType']) ? $_GET['loanType'] : 'Regular';

                // Verify loan application ID
                if (!isset($_SESSION['loan_application_id'])) {
                    // Try to find the most recent loan application for this user
                    $query = "SELECT LoanID FROM loanapplication WHERE userID = ? ORDER BY LoanID DESC LIMIT 1";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($row = $result->fetch_assoc()) {
                        $_SESSION['loan_application_id'] = $row['LoanID'];
                    } else {
                        // No loan application found
                        echo "<script>
                                alert('No active loan application found. Please start a new application.');
                                window.location.href = 'dashboard.php';
                            </script>";
                        exit();
                    }
                    $stmt->close();
                }

                $loan_id = $_SESSION['loan_application_id'];

                // Handle form submission
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Sanitize and validate inputs
                    $yearStay = filter_input(INPUT_POST, 'yearStay', FILTER_VALIDATE_INT);
                    $ownHouse = filter_input(INPUT_POST, 'ownHouse', FILTER_SANITIZE_STRING);
                    $renting = filter_input(INPUT_POST, 'renting', FILTER_SANITIZE_STRING);
                    $livingWithRelative = filter_input(INPUT_POST, 'livingWithRelative', FILTER_SANITIZE_STRING);
                    $maritalStatus = filter_input(INPUT_POST, 'maritalStatus', FILTER_SANITIZE_STRING);
                    $spouseName = filter_input(INPUT_POST, 'spouseName', FILTER_SANITIZE_STRING);
                    $dependentCount = filter_input(INPUT_POST, 'dependentCount', FILTER_VALIDATE_INT);
                    $dependentInSchool = filter_input(INPUT_POST, 'dependentInSchool', FILTER_VALIDATE_INT);
                    
                    // Additional fields with extended validation
                    $employer_name = $_POST['employer_name'] ?? '';
                    $employer_address = $_POST['employer_address'] ?? '';
                    $present_position = $_POST['present_position'] ?? '';
                    $date_of_employment = $_POST['date_of_employment'] ?? '';
                    $monthly_income = filter_input(INPUT_POST, 'monthly_income', FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $contact_person = $_POST['contact_person'] ?? '';
                    $contact_telephone_no = $_POST['contact_telephone_no'] ?? '';
                    $self_employed_business_type = $_POST['self_employed_business_type'] ?? '';
                    $business_start_date = $_POST['business_start_date'] ?? '';
                    $family_member_count = filter_input(INPUT_POST, 'family_member_count', FILTER_VALIDATE_INT);
                    $self_income = $_POST['self_income'] ?? '';
                    $self_income_amount = filter_input(INPUT_POST, 'self_income_amount', FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $other_income = $_POST['other_income'] ?? '';
                    $self_other_income_amount = filter_input(INPUT_POST, 'self_other_income_amount', FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $spouse_income = $_POST['spouse_income'] ?? '';
                    $spouse_income_amount = filter_input(INPUT_POST, 'spouse_income_amount', FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $spouse_other_income = $_POST['spouse_other_income'] ?? '';
                    $spouse_other_income_amount = filter_input(INPUT_POST, 'spouse_other_income_amount', FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $food_groceries_expense = filter_input(INPUT_POST, 'food_groceries_expense', FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $gas_oil_transportation_expense = filter_input(INPUT_POST, 'gas_oil_transportation_expense', FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $schooling_expense = filter_input(INPUT_POST, 'schooling_expense', FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $utilities_expense = filter_input(INPUT_POST, 'utilities_expense', FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $miscellaneous_expense = filter_input(INPUT_POST, 'miscellaneous_expense', FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $total_expenses = filter_input(INPUT_POST, 'total_expenses', FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $net_family_income = filter_input(INPUT_POST, 'net_family_income', FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

                    // Validate dependent information
                    $dependents = [];
                    for ($i = 1; $i <= 4; $i++) {
                        $dependentName = $_POST["dependent{$i}Name"] ?? '';
                        $dependentAge = filter_input(INPUT_POST, "dependent{$i}Age", FILTER_VALIDATE_INT);
                        $dependentGrade = $_POST["dependent{$i}Grade"] ?? '';

                        if (!empty($dependentName)) {
                            $dependents[] = [
                                'name' => $dependentName,
                                'age' => $dependentAge,
                                'grade' => $dependentGrade
                            ];
                        }
                    }

                    // Prepare the update query
                    $query = "UPDATE loanapplication 
                    SET 
                        userID = ?, 
                        years_stay_present_address = ?, 
                        own_house = ?, 
                        renting = ?, 
                        living_with_relative = ?, 
                        marital_status = ?, 
                        spouse_name = ?, 
                        number_of_dependents = ?, 
                        dependents_in_school = ?, 
                        dependent1_name = ?, 
                        dependent1_age = ?, 
                        dependent1_grade_level = ?, 
                        dependent2_name = ?, 
                        dependent2_age = ?, 
                        dependent2_grade_level = ?, 
                        dependent3_name = ?, 
                        dependent3_age = ?, 
                        dependent3_grade_level = ?, 
                        dependent4_name = ?, 
                        dependent4_age = ?, 
                        dependent4_grade_level = ?,
                        employer_name = ?,
                        employer_address = ?,
                        present_position = ?,
                        date_of_employment = ?,
                        monthly_income = ?,
                        contact_person = ?,
                        contact_telephone_no = ?,
                        self_employed_business_type = ?,
                        business_start_date = ?,
                        family_member_count = ?,
                        self_income = ?,
                        self_income_amount = ?,
                        other_income = ?,
                        self_other_income_amount = ?,
                        spouse_income = ?,
                        spouse_income_amount = ?,
                        spouse_other_income = ?,
                        spouse_other_income_amount = ?,
                        food_groceries_expense = ?,
                        gas_oil_transportation_expense = ?,
                        schooling_expense = ?,
                        utilities_expense = ?,
                        miscellaneous_expense = ?,
                        total_expenses = ?,
                        net_family_income = ?
                    WHERE LoanID = ?";

                    try {
                        $stmt = $conn->prepare($query);
                        
                        // Prepare parameters array
                        $params = [
                            $user_id, 
                            $yearStay, 
                            $ownHouse, 
                            $renting, 
                            $livingWithRelative, 
                            $maritalStatus, 
                            $spouseName, 
                            $dependentCount, 
                            $dependentInSchool
                        ];

                        // Add dependent information
                        for ($i = 0; $i < 4; $i++) {
                            if (isset($dependents[$i])) {
                                $params[] = $dependents[$i]['name'];
                                $params[] = $dependents[$i]['age'];
                                $params[] = $dependents[$i]['grade'];
                            } else {
                                $params[] = NULL;
                                $params[] = NULL;
                                $params[] = NULL;
                            }
                        }

                        // Add the new fields to params array
                        $params = array_merge($params, [
                            $employer_name,
                            $employer_address,
                            $present_position,
                            $date_of_employment,
                            $monthly_income,
                            $contact_person,
                            $contact_telephone_no,
                            $self_employed_business_type,
                            $business_start_date,
                            $family_member_count,
                            $self_income,
                            $self_income_amount,
                            $other_income,
                            $self_other_income_amount,
                            $spouse_income,
                            $spouse_income_amount,
                            $spouse_other_income,
                            $spouse_other_income_amount,
                            $food_groceries_expense,
                            $gas_oil_transportation_expense,
                            $schooling_expense,
                            $utilities_expense,
                            $miscellaneous_expense,
                            $total_expenses,
                            $net_family_income
                        ]);

                        // Add the LoanID as the last parameter
                        $params[] = $loan_id;

                        // Create the type string based on the parameter types
                        $typeString = str_repeat('s', count($params));
                        
                        // Bind parameters
                        $stmt->bind_param($typeString, ...$params);

                        // Execute the query
                        if ($stmt->execute()) {
                            $stmt->close();
                            header("Location: regular-form3.php?loanType=" . urlencode($loan_type));
                            exit();
                        } else {
                            throw new Exception("Database update failed: " . $stmt->error);
                        }
                    } catch (Exception $e) {
                        // Log the full error details
                        error_log("Loan Application Update Error: " . $e->getMessage());
                        error_log("MySQL Error: " . $stmt->error);
                        
                        // Display error message
                        echo "<script>
                                alert('Error updating loan application. Please check all fields and try again.');
                                console.error('Detailed Error: " . addslashes($e->getMessage()) . "');
                                history.back();
                            </script>";
                        exit();
                    }
                }
                ob_flush();
                ?>