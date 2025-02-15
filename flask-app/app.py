from flask import Flask, request, jsonify
import pickle
import numpy as np

app = Flask(__name__)

# Load the ML model
model_path = 'collateral_value_model.pkl'  # Update this with your actual model file path
model = pickle.load(open('collateral_value_model.pkl', 'rb'))  # Corrected this line

# Define the root route
@app.route('/')
def index():
    return jsonify({'message': 'Welcome to the prediction API!'})

# Define the prediction route
@app.route('/predict', methods=['POST'])
def predict():
    try:
        # Get data from the request
        data = request.get_json()

        # Ensure the required fields are present in the request
        required_fields = ['square_meters', 'type_of_land', 'location', 'right_of_way', 
                           'hospital', 'clinic', 'school', 'market', 'church', 'public_terminal']
        
        for field in required_fields:
            if field not in data:
                return jsonify({'status': 'error', 'message': f'Missing field: {field}'}), 400

        # Prepare input data for prediction
        input_data = [
            data['square_meters'], 
            data['type_of_land'], 
            data['location'], 
            data['right_of_way'], 
            data['hospital'], 
            data['clinic'], 
            data['school'], 
            data['market'], 
            data['church'], 
            data['public_terminal']
        ]

        # Convert the input data into the correct format for the model (e.g., numpy array)
        input_array = np.array(input_data).reshape(1, -1)

        # Predict with the model
        prediction = model.predict(input_array)

        # Return the prediction result
        return jsonify({'status': 'success', 'predictions': prediction.tolist()})

    except Exception as e:
        return jsonify({'status': 'error', 'message': str(e)}), 500


if __name__ == '__main__':
    app.run(debug=True)
