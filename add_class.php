<!-- /var/www/html/add_class.php -->
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $class_name = $_POST['class_name'];
    $num_instances = $_POST['num_instances'];
    $amis = explode(',', $_POST['amis']); // Split the AMIs into an array

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
        $stmt_ami = $conn->prepare("INSERT INTO amis (class_id, ami_id) VALUES (?, ?)");
        foreach ($amis as $ami) {
            $ami = trim($ami);
            $stmt_ami->bind_param("is", $class_id, $ami);
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
