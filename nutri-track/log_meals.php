<?php

include('db_connect.php');
include('header.php');


$food_item = '';
$calories = '';
$protein = '';
$carbs = '';
$fat = '';
$date = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $food_item = $_POST['food_item'];
    $calories = $_POST['calories'];
    $protein = $_POST['protein'];
    $carbs = $_POST['carbs'];
    $fat = $_POST['fat'];
    $date = $_POST['date'];

    $sql = "INSERT INTO meals (food_item, calories, protein, carbs, fat, date) 
            VALUES ('$food_item', '$calories', '$protein', '$carbs', '$fat', '$date')";

    if ($conn->query($sql) === TRUE) {
        echo "Meal logged successfully!";
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
    <title>Log Meals</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Log Your Meals</h1>
    </header>

    <main>
        <section>
            <h2>Enter Meal Details</h2>
            <form method="POST" action="log_meals.php">
                <label for="food_item">Food Item:</label>
                <input type="text" name="food_item" id="food_item" required value="<?php echo $food_item; ?>"><br>

                <label for="calories">Calories:</label>
                <input type="number" name="calories" id="calories" required value="<?php echo $calories; ?>"><br>

                <label for="protein">Protein (g):</label>
                <input type="number" name="protein" id="protein" required value="<?php echo $protein; ?>"><br>

                <label for="carbs">Carbs (g):</label>
                <input type="number" name="carbs" id="carbs" required value="<?php echo $carbs; ?>"><br>

                <label for="fat">Fat (g):</label>
                <input type="number" name="fat" id="fat" required value="<?php echo $fat; ?>"><br>

                <label for="date">Date:</label>
                <input type="date" name="date" id="date" required value="<?php echo $date; ?>"><br>

                <button type="submit" class="btn">Log Meal</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Nutri-Track. All rights reserved.</p>
    </footer>
</body>
</html>
