import mysql.connector
import numpy as np
import pandas as pd
import joblib
from sklearn.preprocessing import LabelEncoder

# ✅ Load the trained model
try:
    model_data = joblib.load(open('loan_eligibility_model2.pkl', 'rb'))
    model = model_data["model"]
    feature_names = model_data["feature_names"]
    encoder = model_data.get("encoder", None)
    print("✅ Loan Eligibility Model loaded successfully!")
except Exception as e:
    print(f"❌ Error loading model: {e}")
    exit()

# ✅ Database connection
try:
    db_connection = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="pascal"
    )
    cursor = db_connection.cursor(dictionary=True)

    # ✅ Fetch latest loan application data
    cursor.execute("SELECT * FROM loanapplication ORDER BY LoanID DESC LIMIT 1")
    data = cursor.fetchone()

    if not data:
        print("\n❌ No data found in the database.")
        exit()

    print(f"\n📌 Fetched data for LoanID {data['LoanID']}:\n{data}")

    # ✅ Remove datetime columns (LogisticRegression can't handle them)
    datetime_columns = ["DateOfLoan", "date_of_employment", "business_start_date"]
    for col in datetime_columns:
        data.pop(col, None)  # Remove datetime columns safely

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
                label_encoders[col] = le  # Store encoder for future use

    # ✅ Prepare input data
    input_data = pd.DataFrame([{key: data[key] for key in feature_names if key in data}])

    # ✅ Ensure column order matches training data
    input_data = input_data.reindex(columns=feature_names, fill_value=0)

    # ✅ Make prediction
    predicted_eligibility = model.predict(input_data)[0]

    # ✅ Convert Prediction to Human-Readable Format
    predicted_status = "Eligible" if predicted_eligibility == 1 else "Not Eligible"

    # ✅ Debug: Print the query before execution
    update_query = "UPDATE loanapplication SET Eligibility = %s WHERE LoanID = %s"
    print(f"\n🔄 Executing Query: {update_query} with values ({predicted_status}, {data['LoanID']})")

    # ✅ Update database with prediction
    cursor.execute(update_query, (predicted_status, data["LoanID"]))
    db_connection.commit()

    # ✅ Check if the update was successful
    cursor.execute("SELECT Eligibility FROM loanapplication WHERE LoanID = %s", (data["LoanID"],))
    updated_data = cursor.fetchone()
    print(f"\n✅ Database Updated: LoanID {data['LoanID']} -> Eligibility: {updated_data['Eligibility']}")

except Exception as e:
    print(f"\n❌ Error: {e}")

finally:
    # ✅ Close database connection
    if cursor:
        cursor.close()
    if db_connection:
        db_connection.close()
