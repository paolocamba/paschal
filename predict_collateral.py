import mysql.connector
import numpy as np
import pandas as pd
import joblib
import json

# Load the trained model
model_path = r"C:\xampp\htdocs\paschal\admin\collateral_value_model.pkl"

try:
    model_data = joblib.load(open(model_path, 'rb'))
    model = model_data["model"]  # Extract trained model
    feature_names = model_data["feature_names"]  # Features used during training
    encoder = model_data["encoder"]  # Extract encoder for one-hot encoding
except Exception as e:
    print(json.dumps({"success": False, "error": f"Error loading model: {e}"}))
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

# Fetch the latest loan data
cursor.execute("SELECT * FROM land_appraisal ORDER BY LoanID DESC LIMIT 1")
data = cursor.fetchone()

if not data:
    print(json.dumps({"success": False, "error": "No data found in the database."}))
    exit()

# Fetch the correct Final Zonal Value from the database
location = str(data["location_name"]).strip().title()
type_of_land = str(data["type_of_land"]).strip().title()

cursor.execute("""
    SELECT final_zonal_value FROM zonal_values 
    WHERE location_name = %s AND type_of_land = %s
""", (location, type_of_land))

zonal_value_result = cursor.fetchone()
final_zonal_value = float(zonal_value_result['final_zonal_value']) if zonal_value_result else 0

# Prepare input features for the model
categorical_columns = ["right_of_way", "hospital", "clinic", "school", "market", 
                       "church", "public_terminal"]

input_data = pd.DataFrame([{
    "square_meters": float(data["square_meters"]),
    "right_of_way": data["right_of_way"],
    "hospital": data["has_hospital"],   
    "clinic": data["has_clinic"],       
    "school": data["has_school"],       
    "market": data["has_market"],       
    "church": data["has_church"],       
    "public_terminal": data["has_terminal"]  
}])

# Apply One-Hot Encoding with Error Handling
try:
    input_encoded = encoder.transform(input_data[categorical_columns])
    encoded_feature_names = encoder.get_feature_names_out(categorical_columns)
    input_encoded_df = pd.DataFrame(input_encoded, columns=encoded_feature_names)
except ValueError:
    # Create a DataFrame filled with zeros for missing categories
    encoded_feature_names = encoder.get_feature_names_out(categorical_columns)
    input_encoded = np.zeros((1, len(encoded_feature_names)))
    input_encoded_df = pd.DataFrame(input_encoded, columns=encoded_feature_names)

# Merge with numerical features
input_final = pd.concat([input_data[["square_meters"]], input_encoded_df], axis=1)
input_final = input_final.reindex(columns=feature_names, fill_value=0)

# Make prediction
predicted_values = model.predict(input_final)

# Extract predictions
emv_per_sqm = max(float(predicted_values[0][0]), final_zonal_value)  # Ensure EMV per sqm is >= final zonal value
total_value = float(data["square_meters"]) * emv_per_sqm
loanable_amount = total_value * 0.5  

# Update database with calculated values
cursor.execute(
    """UPDATE land_appraisal 
    SET final_zonal_value = %s, EMV_per_sqm = %s, total_value = %s, loanable_amount = %s 
    WHERE LoanID = %s""",
    (final_zonal_value, emv_per_sqm, total_value, loanable_amount, data["LoanID"])
)
db_connection.commit()

# Also update loanable_amount in loanapplication table
cursor.execute(
    """UPDATE loanapplication 
    SET loanable_amount = %s 
    WHERE LoanID = %s""",
    (loanable_amount, data["LoanID"])
)
db_connection.commit()

# Prepare JSON response
response = {
    "success": True,
    "LoanID": data["LoanID"],
    "final_zonal_value": final_zonal_value,
    "EMV_per_sqm": emv_per_sqm,
    "total_value": total_value,
    "loanable_amount": loanable_amount
}

# Print JSON response for API
print(json.dumps(response))

# Close database connection
cursor.close()
db_connection.close()
