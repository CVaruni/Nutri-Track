from flask import Flask, jsonify, request
import sqlite3
from datetime import datetime

app = Flask(__name__)

def create_connection():
    """Create a database connection to nutri_track.db."""
    conn = sqlite3.connect("nutri_track.db")
    return conn

def log_meal(food_name, calories, protein, carbs, fats):
    """Log a meal into the database."""
    conn = create_connection()
    with conn:
        try:
            cur = conn.cursor()
            cur.execute("INSERT INTO meals (date, food_name, calories, protein, carbs, fats) VALUES (?, ?, ?, ?, ?, ?)",
                         (datetime.now().strftime("%Y-%m-%d"), food_name, calories, protein, carbs, fats))
            conn.commit()
            return True
        except Exception as e:
            print(f"Error logging meal: {e}")
            return False

def log_exercise(exercise_name, duration, calories_burned):
    """Log an exercise into the database."""
    conn = create_connection()
    with conn:
        try:
            cur = conn.cursor()
            cur.execute("INSERT INTO exercises (date, exercise_name, duration, calories_burned) VALUES (?, ?, ?, ?)",
                         (datetime.now().strftime("%Y-%m-%d"), exercise_name, duration, calories_burned))
            conn.commit()
            return True
        except Exception as e:
            print(f"Error logging exercise: {e}")
            return False

def get_meal_data():
    """Fetches meal data for visualization."""
    conn = create_connection()
    with conn:
        cur = conn.cursor()
        cur.execute("SELECT food_name, calories FROM meals")
        rows = cur.fetchall()
        return rows

@app.route('/log_meal', methods=['POST'])
def log_meal_api():
    try:
        data = request.get_json()
        food_name = data.get('food_name')
        calories = data.get('calories')
        protein = data.get('protein')
        carbs = data.get('carbs')
        fats = data.get('fats')

        if not all([food_name, calories, protein, carbs, fats]):
            return jsonify({'error': 'Missing required fields'}), 400

        if log_meal(food_name, calories, protein, carbs, fats):
            return jsonify({'message': 'Meal logged successfully!'}), 200
        else:
            return jsonify({'error': 'Error logging meal.'}), 500

    except Exception as e:
        print(f"Error processing log_meal request: {e}")
        return jsonify({'error': 'Internal Server Error'}), 500

@app.route('/log_exercise', methods=['POST'])
def log_exercise_api():
    try:
        data = request.get_json()
        exercise_name = data.get('exercise_name')
        duration = data.get('duration')
        calories_burned = data.get('calories_burned')

        if not all([exercise_name, duration, calories_burned]):
            return jsonify({'error': 'Missing required fields'}), 400

        if log_exercise(exercise_name, duration, calories_burned):
            return jsonify({'message': 'Exercise logged successfully!'}), 200
        else:
            return jsonify({'error': 'Error logging exercise.'}), 500

    except Exception as e:
        print(f"Error processing log_exercise request: {e}")
        return jsonify({'error': 'Internal Server Error'}), 500

@app.route('/get_meal_data')
def get_meal_data_api():
    try:
        meal_data = get_meal_data()
        return jsonify(meal_data)
    except Exception as e:
        print(f"Error fetching meal data: {e}")
        return jsonify({'error': 'Failed to fetch meal data'}), 500

if __name__ == "__main__":
    app.run(debug=True)

def log_exercise(exercise_name, duration, calories_burned):
    """Log an exercise into the database."""
    conn = create_connection()
    with conn:
        try:
            cur = conn.cursor()
            cur.execute("""
                INSERT INTO exercises (date, exercise_name, duration, calories_burned) 
                VALUES (?, ?, ?, ?)
            """, (
                datetime.now().strftime("%Y-%m-%d"), 
                exercise_name, 
                duration, 
                calories_burned
            ))
            conn.commit()
            return True
        except Exception as e:
            print(f"Error logging exercise: {e}")  # This logs the error in the console.
            return False
