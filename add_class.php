<!-- /var/www/html/add_class.php -->
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $class_name = $_POST['class_name'];
    $num_instances = $_POST['num_instances'];
    $ami_ids = $_POST['ami_ids'];
    $ami_tags = $_POST['ami_tags'];

    // Enable error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Connect to the database
    $conn = new mysqli('localhost', 'instructor', 'password', 'aws_instructor');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert the new class into the classes table
    $stmt = $conn->prepare("INSERT INTO classes (class_name, num_instances) VALUES (?, ?)");
    $stmt->bind_param("si", $class_name, $num_instances);
    if ($stmt->execute()) {
        $class_id = $stmt->insert_id;

        // Insert the AMIs into the amis table
        $stmt_ami = $conn->prepare("INSERT INTO amis (class_id, ami_id, ami_tag) VALUES (?, ?, ?)");
        foreach ($ami_ids as $index => $ami_id) {
            $ami_tag = $ami_tags[$index];
            $stmt_ami->bind_param("iss", $class_id, $ami_id, $ami_tag);
            $stmt_ami->execute();
        }

        echo "Class and AMIs added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close connections
    $stmt->close();
    $stmt_ami->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
<br><br>
<a href="index.php"><button>Back to Home</button></a>
<a href="add_class.html"><button>Add Another Class</button></a>
