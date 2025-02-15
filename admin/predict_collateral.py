import mysql.connector
import numpy as np
import pandas as pd
import joblib

# ✅ Load the trained model
try:
    model_data = joblib.load(open('collateral_value_model.pkl', 'rb'))
    model = model_data["model"]  # Extract trained model
    feature_names = model_data["feature_names"]  # Features used during training
    encoder = model_data["encoder"]  # Extract encoder for one-hot encoding
    print("✅ Model loaded successfully!")
except Exception as e:
    print(f"❌ Error loading model: {e}")
    exit()

# ✅ Database connection
db_connection = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="pascal"
)
cursor = db_connection.cursor(dictionary=True)

# ✅ Fetch the latest loan data
cursor.execute("SELECT * FROM land_appraisal ORDER BY LoanID DESC LIMIT 1")
data = cursor.fetchone()

if not data:
    print("\n❌ No data found in the database.")
    exit()

print(f"\n📌 Fetched data for LoanID {data['LoanID']}:\n{data}")

# ✅ Fetch the correct Final Zonal Value from the database
location = str(data["location"]).strip().title()  # Normalize text
type_of_land = str(data["type_of_land"]).strip().title()

cursor.execute("""
    SELECT final_zonal_value FROM zonal_values 
    WHERE location = %s AND type_of_land = %s
""", (location, type_of_land))

zonal_value_result = cursor.fetchone()
final_zonal_value = float(zonal_value_result['final_zonal_value']) if zonal_value_result else 0

# ✅ Prepare input features for the model
categorical_columns = ["right_of_way", "hospital", "clinic", "school", "market", 
                       "church", "public_terminal"]

input_data = pd.DataFrame([{
    "square_meters": float(data["square_meters"]),
    "right_of_way": data["right_of_way"],
    "hospital": data["hospital"],
    "clinic": data["clinic"],
    "school": data["school"],
    "market": data["market"],
    "church": data["church"],
    "public_terminal": data["public_terminal"]
}])

# ✅ Apply One-Hot Encoding with Error Handling for Unknown Categories
try:
    input_encoded = encoder.transform(input_data[categorical_columns])
    encoded_feature_names = encoder.get_feature_names_out(categorical_columns)
    input_encoded_df = pd.DataFrame(input_encoded, columns=encoded_feature_names)
except ValueError as e:
    print(f"❌ Encoding error: {e}")
    print(f"Using default encoding for unknown categories...")

    # Create a DataFrame filled with zeros for missing categories
    encoded_feature_names = encoder.get_feature_names_out(categorical_columns)
    input_encoded = np.zeros((1, len(encoded_feature_names)))
    input_encoded_df = pd.DataFrame(input_encoded, columns=encoded_feature_names)

# ✅ Merge with numerical features
input_final = pd.concat([input_data[["square_meters"]], input_encoded_df], axis=1)

# ✅ Ensure column order matches training
input_final = input_final.reindex(columns=feature_names, fill_value=0)

print("\n🔍 Debug Info:")
print(f"Final Zonal Value: {final_zonal_value}")
print(f"Raw Model Input:\n{input_final}")

# ✅ Make prediction
predicted_values = model.predict(input_final)

# ✅ Extract predictions
emv_per_sqm = max(float(predicted_values[0][0]), final_zonal_value)  # Ensure EMV per sqm is >= final zonal value
total_value = float(data["square_meters"]) * emv_per_sqm
loanable_amount = total_value * 0.5  # Adjust percentage if needed




print("\n🔹 Updated Calculations:")
print(f"Final Zonal Value: {final_zonal_value:,.2f}")
print(f"EMV per sqm: {emv_per_sqm:,.2f}")
print(f"Total Value: {total_value:,.2f}")
print(f"Loanable Amount: {loanable_amount:,.2f}")

# ✅ Update database with calculated values
cursor.execute(
    """UPDATE land_appraisal 
    SET final_zonal_value = %s, EMV_per_sqm = %s, total_value = %s, loanable_amount = %s 
    WHERE LoanID = %s""",
    (final_zonal_value, emv_per_sqm, total_value, loanable_amount, data["LoanID"])
)
db_connection.commit()
print(f"\n✅ Updated LoanID {data['LoanID']} with new values.")

# ✅ Verify the update
cursor.execute("SELECT * FROM land_appraisal WHERE LoanID = %s", (data["LoanID"],))
updated_data = cursor.fetchone()
print(f"\n📌 Updated data for LoanID {data['LoanID']}:\n{updated_data}")

# ✅ Close database connection
cursor.close()
db_connection.close()
