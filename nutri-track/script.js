
const recipes = [
    "Grilled Chicken Salad", "Quinoa and Veggie Stir Fry", "Baked Salmon with Asparagus",
    "Vegetable Soup", "Chickpea Salad", "Sweet Potato and Black Bean Tacos",
    "Zucchini Noodles with Pesto", "Avocado Toast with Poached Egg", "Vermicelli with Fruit Bowl",
    "Poha with Fruit and Nuts", "Moong Dal Chilla with Bread", "Paneer Bhurji with Roti",
    "Rajma Chawal with Fruits", "Sprouts Salad", "Rajma Kebab Sandwich", "Veg Kebab Meal Bowl",
    "Okra with Roti and Cucumbers and Paneer", "Dal Makhni with Roti and Veggies and Chia Seed Pudding"
];

document.getElementById("suggest-meal-btn").addEventListener("click", function() {
    const randomRecipe = recipes[Math.floor(Math.random() * recipes.length)];
    const recipeDiv = document.getElementById("recipe-name");
    recipeDiv.style.display = "block";
    recipeDiv.innerText = "Today's Suggested Recipe: " + randomRecipe;
});

const exercisePlans = {
    weight_loss: {
        cardio: "30-minute running session",
        strength_training: "20-minute HIIT workout"
    },
    strength_gain: {
        cardio: "15-minute cycling session",
        strength_training: "45-minute weight lifting session"
    },
    endurance: {
        cardio: "45-minute swimming session",
        strength_training: "30-minute bodyweight exercises"
    }
};

document.getElementById("exercise-form").addEventListener("submit", function(event) {
    event.preventDefault();

    const goal = document.getElementById("goal").value;
    const exerciseType = document.getElementById("exercise_type").value;

    const exerciseDiv = document.getElementById("exercise-output");
    const exerciseName = document.getElementById("exercise-name");
    const exerciseDetails = document.getElementById("exercise-details");

    exerciseName.innerText = "Recommended Exercise: " + exercisePlans[goal][exerciseType];
    exerciseDetails.innerText = "Duration: " + (exerciseType === "cardio" ? "30 minutes" : "45 minutes") + " of " + (exerciseType === "cardio" ? "cardio exercise" : "strength training");
    
    exerciseDiv.style.display = "block";
});
