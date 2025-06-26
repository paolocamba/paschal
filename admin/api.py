import os
import subprocess
import json
import traceback
from flask import Flask, request, jsonify
import logging

# Initialize Flask app
app = Flask(__name__)

# Configure logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

# Configuration
CONFIG = {
    "ELIGIBILITY_SCRIPT": r"C:\xampp\htdocs\paschal\admin\predict_eligibility.py",
    "COLLATERAL_SCRIPT": r"C:\xampp\htdocs\paschal\liaison-officer\predict_collateral.py",
    "PYTHON_EXECUTABLE": r"C:\Users\Lenovo\AppData\Local\Programs\Python\Python313\python.exe",
    "TIMEOUT": 30  # seconds
}

def validate_environment():
    """Validate required files and environment"""
    missing = []
    for key in ["ELIGIBILITY_SCRIPT", "COLLATERAL_SCRIPT", "PYTHON_EXECUTABLE"]:
        if not os.path.exists(CONFIG[key]):
            missing.append((key, CONFIG[key]))
    return missing

def run_script(script_path, input_data):
    """Generic function to run a Python script with input data"""
    try:
        result = subprocess.run(
            [CONFIG["PYTHON_EXECUTABLE"], script_path],
            input=json.dumps(input_data),
            capture_output=True,
            text=True,
            timeout=CONFIG["TIMEOUT"]
        )
        return result
    except subprocess.TimeoutExpired:
        logger.error(f"Script execution timed out: {script_path}")
        return None
    except Exception as e:
        logger.error(f"Error running script {script_path}: {str(e)}")
        return None

@app.route("/")
def health_check():
    """Health check endpoint"""
    missing = validate_environment()
    if missing:
        return jsonify({
            "status": "error",
            "message": "Missing required files",
            "missing_files": missing
        }), 500
    return jsonify({"status": "healthy", "endpoints": ["/run-model", "/predict"]})

@app.route("/run-model", methods=["POST"])
def run_model():
    """Run eligibility prediction model"""
    logger.info("Received request at /run-model")
    
    # Validate input
    try:
        data = request.get_json()
        if not data or "loan_id" not in data:
            logger.warning("Invalid input data")
            return jsonify({"status": "error", "message": "loan_id is required"}), 400
    except Exception as e:
        logger.error(f"JSON parse error: {str(e)}")
        return jsonify({"status": "error", "message": "Invalid JSON"}), 400

    # Run the script
    result = run_script(CONFIG["ELIGIBILITY_SCRIPT"], data)
    
    if not result:
        return jsonify({"status": "error", "message": "Script execution failed"}), 500
        
    if result.returncode != 0:
        logger.error(f"Script failed with stderr: {result.stderr}")
        return jsonify({
            "status": "error",
            "message": "Prediction failed",
            "error": result.stderr
        }), 500

    # Parse output
    try:
        output = json.loads(result.stdout)
        logger.info(f"Successfully processed loan_id: {data.get('loan_id')}")
        return jsonify(output)
    except json.JSONDecodeError:
        logger.error(f"Invalid script output: {result.stdout}")
        return jsonify({
            "status": "error",
            "message": "Invalid prediction output",
            "output": result.stdout
        }), 500

@app.route('/predict', methods=['POST'])
def predict():
    """Run collateral prediction model"""
    logger.info("Received request at /predict")
    
    try:
        data = request.get_json()
        if not data:
            logger.warning("Empty request data")
            return jsonify({'status': 'error', 'message': 'Invalid JSON input'}), 400
    except Exception as e:
        logger.error(f"JSON parse error: {str(e)}")
        return jsonify({'status': 'error', 'message': 'Invalid JSON'}), 400

    result = run_script(CONFIG["COLLATERAL_SCRIPT"], data)
    
    if not result:
        return jsonify({'status': 'error', 'message': 'Script execution failed'}), 500
        
    if result.returncode != 0:
        logger.error(f"Script failed with stderr: {result.stderr}")
        return jsonify({
            'status': 'error',
            'message': 'Prediction failed',
            'error': result.stderr
        }), 500

    try:
        output = json.loads(result.stdout)
        logger.info("Successfully processed collateral prediction")
        return jsonify({'status': 'success', 'prediction': output})
    except json.JSONDecodeError:
        logger.error(f"Invalid script output: {result.stdout}")
        return jsonify({
            'status': 'error',
            'message': 'Invalid prediction output',
            'output': result.stdout
        }), 500

if __name__ == "__main__":
    # Validate environment before starting
    missing_files = validate_environment()
    if missing_files:
        logger.error("Missing required files:")
        for file in missing_files:
            logger.error(f"{file[0]}: {file[1]}")
        raise SystemExit("Missing required files. Cannot start server.")
    
    app.run(host="0.0.0.0", port=5000, debug=True)