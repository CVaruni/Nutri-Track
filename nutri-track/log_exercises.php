<?php
include('db_connect.php');
include('header.php');

$exercise_name = '';
$duration = '';
$calories_burned = '';
$date = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $exercise_name = $_POST['exercise_name'];
    $duration = $_POST['duration'];
    $calories_burned = $_POST['calories_burned'];
    $date = $_POST['date'];
    $sql = "INSERT INTO exercises (exercise_name, duration, calories_burned, date) 
            VALUES ('$exercise_name', '$duration', '$calories_burned', '$date')";
    if ($conn->query($sql) === TRUE) {
        echo "Exercise logged successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Exercise</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Log Your Exercise</h1>
    </header>

    <main>
        <section>
            <h2>Enter Exercise Details</h2>
            <form method="POST" action="log_exercises.php">
                <label for="exercise_name">Exercise Name:</label>
                <input type="text" name="exercise_name" id="exercise_name" required value="<?php echo $exercise_name; ?>"><br>

                <label for="duration">Duration (in minutes):</label>
                <input type="number" name="duration" id="duration" required value="<?php echo $duration; ?>"><br>

                <label for="calories_burned">Calories Burned:</label>
                <input type="number" name="calories_burned" id="calories_burned" required value="<?php echo $calories_burned; ?>"><br>

                <label for="date">Date:</label>
                <input type="date" name="date" id="date" required value="<?php echo $date; ?>"><br>

                <button type="submit" class="btn">Log Exercise</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Nutri-Track. All rights reserved.</p>
    </footer>
</body>
</html>
