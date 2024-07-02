<!-- /var/www/html/add_schedule.php -->
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $class_id = $_POST['class_id'];
    $num_students = $_POST['num_students'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Enable error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Connect to the database
    $conn = new mysqli('localhost', 'instructor', 'password', 'aws_instructor');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert the schedule into the schedule table
    $stmt = $conn->prepare("INSERT INTO schedule (class_id, num_students, start_date, end_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $class_id, $num_students, $start_date, $end_date);
    if ($stmt->execute()) {
        echo "Class scheduled successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close connections
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
<br><br>
<a href="index.php"><button>Back to Home</button></a>
<a href="add_schedule.html"><button>Schedule Another Class</button></a>
