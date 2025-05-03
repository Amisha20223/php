<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Comparison</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .header {
            text-align: center;
            padding: 20px;
            background-color: #2c3e50;
            color: white;
        }
        .vehicle-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .vehicle-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .vehicle-card h3 {
            color: #e74c3c;
            margin-top: 0;
        }
        .specs {
            margin: 10px 0;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <?php include 'vehicle.php'; ?>
    
    <div class="header">
        <h1>Vehicle Comparison Portal</h1>
        <p>Compare prices and mileage for different vehicles</p>
    </div>

    <div class="vehicle-container">
        <?php foreach ($vehicles as $category => $items): ?>
            <div class="vehicle-card">
                <h2><?= ucfirst($category) ?></h2>
                <?php foreach ($items as $vehicle): ?>
                    <div class="specs">
                        <h3><?= $vehicle['name'] ?></h3>
                        <p>Price: <?= $vehicle['mrp'] ?></p>
                        <p>Mileage: <?= $vehicle['mileage'] ?></p>
                        <p>Fuel Type: <?= $vehicle['type'] ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>