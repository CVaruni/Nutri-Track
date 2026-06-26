<?php
include('db_connect.php');
include('header.php');

// Add this below your existing queries in view_progress.php
$net_calories_query = "
    SELECT 
        date,
        SUM(total_in) as calories_in,
        SUM(total_out) as calories_out,
        (SUM(total_in) - SUM(total_out)) as net_calories
    FROM (
        SELECT date, SUM(calories) as total_in, 0 as total_out FROM meals GROUP BY date
        UNION ALL
        SELECT date, 0 as total_in, SUM(calories_burned) as total_out FROM exercises GROUP BY date
    ) combined_data
    GROUP BY date
    ORDER BY date ASC
";

$net_result = $conn->query($net_calories_query);

$dates = [];
$net_calories = [];

if ($net_result && $net_result->num_rows > 0) {
    while ($row = $net_result->fetch_assoc()) {
        $dates[] = $row['date'];
        $net_calories[] = $row['net_calories'];
    }
}

$exercise_query = "SELECT exercise_name, duration, calories_burned, date FROM exercises ORDER BY date DESC";
$exercise_result = $conn->query($exercise_query);

$meal_query = "SELECT food_item, calories, protein, carbs, fat, date FROM meals ORDER BY date DESC";
$meal_result = $conn->query($meal_query);

$exercise_dates_query = "SELECT DISTINCT date FROM exercises";
$exercise_dates_result = $conn->query($exercise_dates_query);

$meal_dates_query = "SELECT DISTINCT date FROM meals";
$meal_dates_result = $conn->query($meal_dates_query);

$exercise_days = $exercise_dates_result->num_rows;
$meal_days = $meal_dates_result->num_rows;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progress - Fitness Tracker</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>View Progress</h1>
    </header>

    <nav>
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="log_meals.php">Log Meals</a></li>
            <li><a href="log_exercises.php">Log Exercises</a></li>
            <li><a href="view_progress.php">View Progress</a></li>
        </ul>
    </nav>

    <div class="container">
        <h2>Exercise Log</h2>
        <table>
            <tr>
                <th>Exercise Name</th>
                <th>Duration (min)</th>
                <th>Calories Burned</th>
                <th>Date</th>
            </tr>
            <?php
            if ($exercise_result->num_rows > 0) {
                while ($row = $exercise_result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['exercise_name']}</td>
                            <td>{$row['duration']}</td>
                            <td>{$row['calories_burned']}</td>
                            <td>{$row['date']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No exercise data found.</td></tr>";
            }
            ?>
        </table>

        <h2>Meal Log</h2>
        <table>
            <tr>
                <th>Food Item</th>
                <th>Calories</th>
                <th>Protein</th>
                <th>Carbs</th>
                <th>Fats</th>
                <th>Date</th>
            </tr>
            <?php
            if ($meal_result->num_rows > 0) {
                while ($row = $meal_result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['food_item']}</td>
                            <td>{$row['calories']}</td>
                            <td>{$row['protein']}</td>
                            <td>{$row['carbs']}</td>
                            <td>{$row['fat']}</td>
                            <td>{$row['date']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No meal data found.</td></tr>";
            }
            ?>
        </table>

        <canvas id="loggedDaysChart"></canvas>

    </div>
    <h3>Net Calorie Trends</h3>
<canvas id="staircaseChart"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('staircaseChart').getContext('2d');
    
    // Pass the PHP arrays to JavaScript
    const labels = <?php echo json_encode($dates); ?>;
    const dataPoints = <?php echo json_encode($net_calories); ?>;

    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Net Calories (Intake - Burned)',
                data: dataPoints,
                borderColor: '#2196F3',
                backgroundColor: 'rgba(33, 150, 243, 0.2)',
                borderWidth: 2,
                stepped: true, // This creates the staircase effect!
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    title: {
                        display: true,
                        text: 'Net Calories'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Date'
                    }
                }
            }
        }
    });
</script>
</body>
</html>
