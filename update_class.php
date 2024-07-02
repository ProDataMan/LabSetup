<!-- /var/www/html/update_class.php -->
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $class_id = $_POST['class_id'];
    $class_name = $_POST['class_name'];
    $num_instances = $_POST['num_instances'];
    $ami_ids = $_POST['ami_ids'];
    $ami_ami_ids = $_POST['ami_ami_ids'];
    $ami_tags = $_POST['ami_tags'];

    // Enable error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Connect to the database
    $conn = new mysqli('localhost', 'instructor', 'password', 'aws_instructor');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update the class details
    $stmt = $conn->prepare("UPDATE classes SET class_name=?, num_instances=? WHERE id=?");
    $stmt->bind_param("sii", $class_name, $num_instances, $class_id);
    if (!$stmt->execute()) {
        die("Error updating class: " . $stmt->error);
    }

    // Update the AMIs
    $stmt_ami_update = $conn->prepare("UPDATE amis SET ami_id=?, ami_tag=? WHERE id=?");
    $stmt_ami_insert = $conn->prepare("INSERT INTO amis (class_id, ami_id, ami_tag) VALUES (?, ?, ?)");
    for ($i = 0; $i < count($ami_ids); $i++) {
        $ami_id = $ami_ids[$i];
        $ami_ami_id = $ami_ami_ids[$i];
        $ami_tag = $ami_tags[$i];
        if ($ami_id) {
            // Update existing AMI
            $stmt_ami_update->bind_param("ssi", $ami_ami_id, $ami_tag, $ami_id);
            if (!$stmt_ami_update->execute()) {
                die("Error updating AMI: " . $stmt_ami_update->error);
            }
        } else {
            // Insert new AMI
            $stmt_ami_insert->bind_param("iss", $class_id, $ami_ami_id, $ami_tag);
            if (!$stmt_ami_insert->execute()) {
                die("Error inserting AMI: " . $stmt_ami_insert->error);
            }
        }
    }

    echo "Class updated successfully.";

    // Close connections
    $stmt->close();
    $stmt_ami_update->close();
    $stmt_ami_insert->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
