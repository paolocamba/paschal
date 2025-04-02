from flask import Flask, request, jsonify
import subprocess
import json
import os
import traceback
import sys

app = Flask(__name__)

# Ensure the correct script path
SCRIPT_PATH = r"C:\xampp\htdocs\paschal\liaison-officer\predict_collateral.py"

@app.route('/predict', methods=['POST'])
def predict():
    try:
        # Verify that the script exists
        if not os.path.exists(SCRIPT_PATH):
            return jsonify({
                'success': False,
                'error': 'Python script not found',
                'expected_path': SCRIPT_PATH,
                'current_directory': os.getcwd(),
                'directory_contents': os.listdir(os.path.dirname(SCRIPT_PATH))
            }), 500

        # Get input data
        data = request.get_json()
        if not data:
            return jsonify({'success': False, 'error': 'Invalid JSON input'}), 400
        
        # Run the Python script with input passed via stdin
        result = subprocess.run(
            [sys.executable, SCRIPT_PATH],
            input=json.dumps(data),  # Send JSON data as stdin
            capture_output=True,
            text=True,
            timeout=30,
            cwd=os.path.dirname(SCRIPT_PATH)  # Run from script's directory
        )

        # Debugging logs
        print("Script stdout:", result.stdout)
        print("Script stderr:", result.stderr)

        if result.returncode != 0:
            return jsonify({
                'success': False,
                'error': f'Script failed (code {result.returncode})',
                'stderr': result.stderr,
                'stdout': result.stdout
            }), 500

        # Parse the script output
        try:
            output = json.loads(result.stdout)
            return jsonify({'success': True, 'prediction': output})
        except json.JSONDecodeError:
            return jsonify({
                'success': False,
                'error': 'Invalid script output',
                'output': result.stdout
            }), 500

    except Exception as e:
        return jsonify({
            'success': False,
            'error': str(e),
            'traceback': traceback.format_exc()
        }), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5001, debug=True)
