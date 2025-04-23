<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'db_connect.php';
include('header.php');

if (!$conn) 
{
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $goal = $_POST['goal'] ?? null;
    $exercise_type = $_POST['exercise_type'] ?? null;

    if ($goal && $exercise_type) 
    {
        $exercise_plan = generateExercisePlan($goal, $exercise_type);

        $stmt = $conn->prepare("INSERT INTO exercise_plans (goal, exercise_type, exercise_plan) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $goal, $exercise_type, $exercise_plan);

        if ($stmt->execute()) 
        {
            
            $response = 
            [
                'success' => true,
                'goal' => ucfirst(str_replace("_", " ", $goal)),
                'exercise_type' => ucfirst(str_replace("_", " ", $exercise_type)),
                'exercise_plan' => $exercise_plan
            ];
        } 
        else 
        {
        
            $response = ['success' => false, 'error' => 'Database error: ' . $stmt->error];
        }

        $stmt->close();
    } 
    else 
    {
        $response = ['success' => false, 'error' => 'Please fill out all fields.'];
    }

    echo json_encode($response);
    exit;  
}


function generateExercisePlan($goal, $exercise_type) 
{
    if ($goal == "weight_loss") 
    {
        return $exercise_type == "cardio" ? "Run 5 km daily." : "Try High-Intensity Interval Training (HIIT).";
    } elseif ($goal == "strength_gain") 
    {
        return $exercise_type == "strength_training" ? "Lift weights 3 times a week." : "Do full-body workouts with bodyweight exercises.";
    } elseif ($goal == "endurance") 
    {
        return "Perform long-duration, low-intensity exercises like cycling.";
    }
    return "No plan available.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercise Plan Generator</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Generate Your Exercise Plan</h2>
    <form id="exercise-form" method="POST">
        <label for="goal">Goal:</label>
        <select name="goal" id="goal" required>
            <option value="weight_loss">Weight Loss</option>
            <option value="strength_gain">Strength Gain</option>
            <option value="endurance">Endurance</option>
        </select><br><br>

        <label for="exercise_type">Exercise Type:</label>
        <select name="exercise_type" id="exercise_type" required>
            <option value="cardio">Cardio</option>
            <option value="strength_training">Strength Training</option>
        </select><br><br>

        <button type="submit">Generate Exercise Plan</button>
    </form>

    <div id="exercise-output"></div>

    <script>
        document.getElementById('exercise-form').addEventListener('submit', function(event) 
        {
            event.preventDefault();

            const goal = document.getElementById('goal').value;
            const exercise_type = document.getElementById('exercise_type').value;

            fetch('ai_exercise.php', 
            {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    goal: goal,
                    exercise_type: exercise_type,
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) 
                {

                    const exerciseOutput = document.getElementById('exercise-output');
                    exerciseOutput.innerHTML = `
                        <h3>Recommended Exercise Plan:</h3>
                        <p><strong>Goal:</strong> ${data.goal}</p>
                        <p><strong>Exercise Type:</strong> ${data.exercise_type}</p>
                        <p><strong>Exercise Plan:</strong> ${data.exercise_plan}</p>
                    `;
                } 
                else 
                {
                    alert(data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error submitting the form. Please try again.');
            });
        });
    </script>
</body>
</html>
