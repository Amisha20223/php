<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include vehicle data
include __DIR__ . '/vehicle.php';

// Process form submission
$recommendations = [];
$alerts = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $passengers = (int)$_POST['passengers'];
    $terrain = $_POST['terrain'];
    $weather = $_POST['weather'];

    // Generate recommendations
    foreach ($vehicles as $category => $items) {
        foreach ($items as $vehicle) {
            $score = 0;
            
            // Passenger capacity scoring
            if ($vehicle['passengers'] >= $passengers) $score += 30;
            
            // Terrain matching
            if ($vehicle['terrain'] === $terrain) $score += 40;
            
            // Weather considerations
            if ($weather === 'rainy' && $category === 'cars') $score += 30;
            if ($weather === 'sunny' && $category === 'bikes') $score += 20;
            
            if ($score > 50) {
                $recommendations[] = [
                    'vehicle' => $vehicle,
                    'category' => $category,
                    'score' => $score
                ];
            }
        }
    }

    // Sort recommendations by score
    usort($recommendations, function($a, $b) {
        return $b['score'] - $a['score'];
    });

    // Generate alerts
    if ($weather === 'rainy') {
        $alerts[] = "Rain Alert: Consider vehicles with better stability and covered seating";
    }
    if ($passengers > 2) {
        $alerts[] = "Traffic Tip: Cars recommended for better lane management in heavy traffic";
    }
    if ($terrain === 'mountain') {
        $alerts[] = "Mountain Advice: Choose vehicles with higher ground clearance";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Comparison & Recommendation System</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #f0f2f5;
            line-height: 1.6;
        }

        .header {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .recommendation-form {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin: 2rem 0;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #2c3e50;
            font-weight: 600;
        }

        input, select {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
        }

        button {
            background: #3498db;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #2980b9;
        }

        .vehicle-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            margin: 2rem 0;
        }

        .vehicle-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .vehicle-card:hover {
            transform: translateY(-5px);
        }

        .vehicle-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .vehicle-info {
            padding: 1.5rem;
        }

        .vehicle-category {
            color: #3498db;
            font-size: 0.9rem;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        .vehicle-name {
            color: #2c3e50;
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }

        .vehicle-specs {
            color: #666;
            margin-bottom: 0.5rem;
        }

        .alert {
            background: #fff3cd;
            color: #856404;
            padding: 1rem;
            border-radius: 8px;
            margin: 1rem 0;
            border-left: 4px solid #ffeeba;
        }

        .recommendation-badge {
            background: #27ae60;
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            display: inline-block;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Smart Vehicle Recommender</h1>
        <p>Find your perfect ride based on needs, weather, and terrain</p>
    </div>

    <div class="container">
        <!-- Recommendation Form -->
        <form method="POST" class="recommendation-form">
            <div class="form-group">
                <label>Number of Passengers</label>
                <input type="number" name="passengers" min="1" max="8" required>
            </div>

            <div class="form-group">
                <label>Primary Terrain</label>
                <select name="terrain" required>
                    <option value="city">City Roads</option>
                    <option value="mountain">Mountain/Hilly</option>
                    <option value="mixed">Mixed Terrain</option>
                </select>
            </div>

            <div class="form-group">
                <label>Expected Weather</label>
                <select name="weather" required>
                    <option value="sunny">Sunny</option>
                    <option value="rainy">Rainy</option>
                    <option value="foggy">Foggy</option>
                </select>
            </div>

            <button type="submit" name="recommend">Find My Vehicle</button>
        </form>

        <?php if (!empty($alerts)): ?>
            <?php foreach ($alerts as $alert): ?>
                <div class="alert"><?= $alert ?></div>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <!-- Recommendations Section -->
            <h2>Top Recommendations</h2>
            <?php if (!empty($recommendations)): ?>
                <div class="vehicle-grid">
                    <?php foreach ($recommendations as $rec): ?>
                        <div class="vehicle-card">
                            <img src="images/<?= $rec['vehicle']['image'] ?>" class="vehicle-image" alt="<?= $rec['vehicle']['name'] ?>">
                            <div class="vehicle-info">
                                <div class="recommendation-badge">
                                    <?= $rec['score'] ?>% Match
                                </div>
                                <h3 class="vehicle-name"><?= $rec['vehicle']['name'] ?></h3>
                                <p class="vehicle-category"><?= ucfirst($rec['category']) ?></p>
                                <p class="vehicle-specs">Price: <?= $rec['vehicle']['mrp'] ?></p>
                                <p class="vehicle-specs">Mileage: <?= $rec['vehicle']['mileage'] ?></p>
                                <p class="vehicle-specs">Best For: <?= $rec['vehicle']['best_for'] ?></p>
                                <p class="vehicle-specs">Meal Suggestion: <?= $rec['vehicle']['meal_suggestion'] ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert">No vehicles found matching your criteria. Please try different parameters.</div>
            <?php endif; ?>

        <?php else: ?>
            <!-- Default Vehicle Display -->
            <h2>All Vehicles</h2>
            <div class="vehicle-grid">
                <?php foreach ($vehicles as $category => $items): ?>
                    <?php foreach ($items as $vehicle): ?>
                        <div class="vehicle-card">
                            <img src="images/<?= $vehicle['image'] ?>" class="vehicle-image" alt="<?= $vehicle['name'] ?>">
                            <div class="vehicle-info">
                                <h3 class="vehicle-name"><?= $vehicle['name'] ?></h3>
                                <p class="vehicle-category"><?= ucfirst($category) ?></p>
                                <p class="vehicle-specs">Price: <?= $vehicle['mrp'] ?></p>
                                <p class="vehicle-specs">Mileage: <?= $vehicle['mileage'] ?></p>
                                <p class="vehicle-specs">Best For: <?= $vehicle['best_for'] ?></p>
                                <p class="vehicle-specs">Meal Spot: <?= $vehicle['meal_suggestion'] ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>