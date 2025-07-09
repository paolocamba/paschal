import mysql.connector
import numpy as np
import pandas as pd
import joblib
import json
import sys

# Load the trained model
model_path = r"C:\xampp\htdocs\paschal\admin\collateral_value_model2.pkl"  # Updated model name

try:
    model_data = joblib.load(open(model_path, 'rb'))
    model = model_data["model"]
    feature_names = model_data["feature_names"]
    encoder = model_data["encoder"]
    scaler = model_data["scaler"]
except Exception as e:
    print(json.dumps({"success": False, "error": f"Error loading model: {e}"}))
    exit()

# Read input data
input_json = sys.stdin.read()
try:
    input_data = json.loads(input_json)
    loan_id = input_data.get("loan_id")
    if not loan_id:
        raise ValueError("LoanID is missing in the request.")
except Exception as e:
    print(json.dumps({"success": False, "error": f"Invalid JSON input: {e}"}))
    exit()

# Database connection
try:
    db_connection = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="pascal"
    )
    cursor = db_connection.cursor(dictionary=True)
except Exception as e:
    print(json.dumps({"success": False, "error": f"Database connection failed: {e}"}))
    exit()

# Fetch loan details
cursor.execute("SELECT * FROM land_appraisal WHERE LoanID = %s", (loan_id,))
data = cursor.fetchone()

if not data:
    print(json.dumps({"success": False, "error": f"No data found for LoanID {loan_id}"}))
    exit()

# Fetch Final Zonal Value
location = str(data["location_name"]).strip().title()
type_of_land = str(data["type_of_land"]).strip().title()

cursor.execute("""
    SELECT final_zonal_value FROM zonal_values 
    WHERE location_name = %s AND type_of_land = %s
""", (location, type_of_land))

zonal_value_result = cursor.fetchone()
final_zonal_value = float(zonal_value_result['final_zonal_value']) if zonal_value_result else 0

# Prepare input features - MATCHING TRAINING DATA FORMAT
input_df = pd.DataFrame([{
    "square_meters": float(data["square_meters"]),
    "final_zonal_value": final_zonal_value,  # Include in features
    "right_of_way": data["right_of_way"],
    "has_hospital": data["has_hospital"],
    "has_clinic": data["has_clinic"],
    "has_school": data["has_school"],
    "has_market": data["has_market"],
    "has_church": data["has_church"],
    "has_terminal": data["has_terminal"]
}])

# Process features exactly like during training
try:
    # Scale numerical features
    numerical_features = ['square_meters', 'final_zonal_value']
    scaled_values = scaler.transform(input_df[numerical_features])
    
    # Encode categorical features
    categorical_columns = ['right_of_way', 'has_hospital', 'has_clinic', 
                          'has_school', 'has_market', 'has_church', 'has_terminal']
    encoded_values = encoder.transform(input_df[categorical_columns])
    
    # Combine features
    input_final = np.concatenate([scaled_values, encoded_values], axis=1)
    
    # Ensure correct feature order
    input_final = pd.DataFrame(input_final, columns=feature_names)
    
except Exception as e:
    print(json.dumps({"success": False, "error": f"Feature processing failed: {e}"}))
    exit()

# Make prediction
try:
    predicted_values = model.predict(input_final)
    emv_per_sqm = max(float(predicted_values[0][0]), final_zonal_value)  # Ensure never below zonal value
    total_value = float(data["square_meters"]) * emv_per_sqm
    loanable_amount = total_value * 0.5
except Exception as e:
    print(json.dumps({"success": False, "error": f"Prediction failed: {e}"}))
    exit()

# Update database
try:
    cursor.execute(
        """UPDATE land_appraisal 
        SET final_zonal_value = %s, EMV_per_sqm = %s, total_value = %s, loanable_amount = %s 
        WHERE LoanID = %s""",
        (final_zonal_value, emv_per_sqm, total_value, loanable_amount, loan_id)
    )
    db_connection.commit()
    
    cursor.execute(
        """UPDATE loanapplication 
        SET loanable_amount = %s 
        WHERE LoanID = %s""",
        (loanable_amount, loan_id)
    )
    db_connection.commit()
except Exception as e:
    print(json.dumps({"success": False, "error": f"Database update failed: {e}"}))
    exit()

# Return response
response = {
    "success": True,
    "LoanID": loan_id,
    "final_zonal_value": final_zonal_value,
    "EMV_per_sqm": emv_per_sqm,
    "total_value": total_value,
    "loanable_amount": loanable_amount
}

print(json.dumps(response))

# Clean up
cursor.close()
db_connection.close()