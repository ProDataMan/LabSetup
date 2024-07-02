<!-- /var/www/html/index.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AWS EC2 Instance Provisioner</title>
</head>
<body>
    <h1>Provision AWS EC2 Instances</h1>
    <form action="provision.php" method="post">
        <label for="class">Select Class:</label>
        <select name="class" id="class">
            <?php
            // Enable error reporting
            error_reporting(E_ALL);
            ini_set('display_errors', 1);

            // Connect to the database and fetch classes
            $conn = new mysqli('localhost', 'instructor', 'password', 'aws_instructor');
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            
            $result = $conn->query("SELECT id, class_name FROM classes");
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['class_name']}</option>";
                }
            } else {
                echo "<option value=''>No classes available</option>";
            }
            $conn->close();
            ?>
        </select>
        <br><br>
        <label for="students">Number of Students:</label>
        <input type="number" id="students" name="students" min="1" required>
        <br><br>
        <input type="submit" value="Provision Instances">
    </form>
</body>
</html>
