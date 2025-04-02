import mysql.connector
import numpy as np
import pandas as pd
import joblib
from sklearn.preprocessing import LabelEncoder
import json
import sys

# Path to the trained model
model_path = r"C:\xampp\htdocs\paschal\admin\loan_eligibility_model2.pkl"

# ✅ Load the trained model
try:
    model_data = joblib.load(open(model_path, "rb"))
    model = model_data["model"]
    feature_names = model_data["feature_names"]
    encoder = model_data.get("encoder", None)
except Exception as e:
    print(json.dumps({"success": False, "error": f"Error loading model: {e}"}))
    exit()

# ✅ Read LoanID from JSON input
input_json = sys.stdin.read()
try:
    input_data = json.loads(input_json)
    loan_id = input_data.get("loan_id")  
    if not loan_id or not str(loan_id).isdigit():
        raise ValueError("LoanID is missing or invalid.")
    loan_id = int(loan_id)  # Convert LoanID to an integer
except Exception as e:
    print(json.dumps({"success": False, "error": f"Invalid JSON input: {e}"}))
    exit()

# ✅ Connect to MySQL database
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

# ✅ Fetch loan details using LoanID
try:
    cursor.execute("SELECT * FROM loanapplication WHERE LoanID = %s", (loan_id,))
    data = cursor.fetchone()
    
    if not data:
        print(json.dumps({"success": False, "error": f"No data found for LoanID {loan_id}"}))
        exit()

    # ✅ Remove datetime columns
    datetime_columns = ["DateOfLoan", "date_of_employment", "business_start_date"]
    for col in datetime_columns:
        data.pop(col, None)

    # ✅ Convert categorical fields using Label Encoding or binary mapping
    binary_columns = ["own_house", "renting", "living_with_relative", "property_foreclosed_repossessed", "co_maker_cosigner_guarantor"]
    for col in binary_columns:
        if col in data and data[col] is not None:
            data[col] = 1 if str(data[col]).lower() == "yes" else 0 if str(data[col]).lower() == "no" else data[col]

    categorical_columns = ["marital_status"]
    label_encoders = {}
    for col in categorical_columns:
        if col in data and data[col] is not None:
            if encoder and col in encoder:
                data[col] = encoder[col].transform([data[col]])[0]
            else:
                le = LabelEncoder()
                data[col] = le.fit_transform([data[col]])[0]
                label_encoders[col] = le

    # ✅ Prepare input data
    input_data = pd.DataFrame([{key: data[key] for key in feature_names if key in data}])

    # ✅ Ensure column order matches training data
    input_data = input_data.reindex(columns=feature_names, fill_value=0)

    # ✅ Make prediction
    predicted_eligibility = model.predict(input_data)[0]
    predicted_status = "Eligible" if predicted_eligibility == 1 else "Not Eligible"

    # ✅ Update database with prediction
    update_query = "UPDATE loanapplication SET Eligibility = %s WHERE LoanID = %s"
    cursor.execute(update_query, (predicted_status, loan_id))
    db_connection.commit()

    # ✅ Verify update
    cursor.execute("SELECT Eligibility FROM loanapplication WHERE LoanID = %s", (loan_id,))
    updated_data = cursor.fetchone()

    # ✅ ✅ ✅ **Final JSON output (No Extra Logs)**
    result = {
        "success": True,
        "LoanID": loan_id,
        "UpdatedEligibility": updated_data["Eligibility"]
    }
    print(json.dumps(result))  # **Only print the JSON result!**

except Exception as e:
    print(json.dumps({"success": False, "error": f"Error processing loan: {e}"}))

finally:
    # ✅ Close database connection
    if cursor:
        cursor.close()
    if db_connection:
        db_connection.close()
