import os
import subprocess
import json
import traceback
import sys
from flask import Flask, request, jsonify

app = Flask(__name__)

# Define script paths
ELIGIBILITY_SCRIPT = r"C:\xampp\htdocs\paschal\admin\predict_eligibility.py"
COLLATERAL_SCRIPT = r"C:\xampp\htdocs\paschal\liaison-officer\predict_collateral.py"
PYTHON_EXECUTABLE = r"C:\Users\rickp\AppData\Local\Programs\Python\Python313\python.exe"

@app.route("/run-model", methods=["POST"])
def run_model():
    """Runs the eligibility prediction script with JSON input."""
    if not os.path.exists(ELIGIBILITY_SCRIPT):
        return jsonify({"status": "error", "message": f"File not found: {ELIGIBILITY_SCRIPT}"}), 500
    
    try:
        # Read JSON data from the request
        data = request.get_json()
        if not data:
            return jsonify({"status": "error", "message": "Invalid JSON input"}), 400

        # Convert JSON to a string to pass as STDIN
        json_input = json.dumps(data)

        # Run the script and pass JSON via STDIN
        result = subprocess.run(
            [PYTHON_EXECUTABLE, ELIGIBILITY_SCRIPT],
            input=json_input,  # Pass JSON input as STDIN
            capture_output=True,
            text=True,
            timeout=30  # Prevent infinite hanging
        )

        # Check for execution errors
        if result.returncode != 0:
            return jsonify({"status": "error", "message": "Script execution failed", "stderr": result.stderr}), 500

        # Parse and return output
        try:
            output = json.loads(result.stdout)
            return jsonify(output)
        except json.JSONDecodeError:
            return jsonify({"status": "error", "message": "Invalid script output", "output": result.stdout}), 500

    except Exception as e:
        return jsonify({"status": "error", "message": str(e), "traceback": traceback.format_exc()}), 500


@app.route('/predict', methods=['POST'])
def predict():
    """Runs the collateral prediction script."""
    if not os.path.exists(COLLATERAL_SCRIPT):
        return jsonify({
            'success': False,
            'error': 'Python script not found',
            'expected_path': COLLATERAL_SCRIPT
        }), 500
    
    data = request.get_json()
    if not data:
        return jsonify({'success': False, 'error': 'Invalid JSON input'}), 400
    
    try:
        result = subprocess.run(
            [PYTHON_EXECUTABLE, COLLATERAL_SCRIPT],
            input=json.dumps(data),
            capture_output=True,
            text=True,
            timeout=30
        )
        
        if result.returncode != 0:
            return jsonify({'success': False, 'error': 'Script execution failed', 'stderr': result.stderr}), 500
        
        try:
            output = json.loads(result.stdout)
            return jsonify({'success': True, 'prediction': output})
        except json.JSONDecodeError:
            return jsonify({'success': False, 'error': 'Invalid script output', 'output': result.stdout}), 500

    except Exception as e:
        return jsonify({'success': False, 'error': str(e), 'traceback': traceback.format_exc()}), 500

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000, debug=True)
