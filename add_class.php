<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $class_name = $_POST['class_name'];
    $terraform_config_path = $_POST['terraform_config_path'];
    $ami_ids = $_POST['ami_ids'];
    $ami_tags = $_POST['ami_tags'];
    $ami_terraform_configs = $_POST['ami_terraform_configs'];

    // Enable error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Connect to the database
    $conn = new mysqli('localhost', 'instructor', 'password', 'aws_instructor');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert the new class into the classes table
    $stmt = $conn->prepare("INSERT INTO classes (class_name, terraform_config_path) VALUES (?, ?)");
    $stmt->bind_param("ss", $class_name, $terraform_config_path);
    if ($stmt->execute()) {
        $class_id = $stmt->insert_id;

        // Create directory structure for the class
        $class_dir = "/path/to/classes/$class_name";
        if (!mkdir($class_dir, 0777, true)) {
            die("Failed to create directory: $class_dir");
        }
        $terraform_subdir = "$class_dir/terraform";
        if (!mkdir($terraform_subdir, 0777, true)) {
            die("Failed to create subdirectory: $terraform_subdir");
        }

        // Insert the AMIs into the amis table
        $stmt_ami = $conn->prepare("INSERT INTO amis (class_id, ami_id, ami_tag, terraform_config) VALUES (?, ?, ?, ?)");
        foreach ($ami_ids as $index => $ami_id) {
            $ami_tag = $ami_tags[$index];
            $ami_terraform_config = $ami_terraform_configs[$index];
            $stmt_ami->bind_param("isss", $class_id, $ami_id, $ami_tag, $ami_terraform_config);
            $stmt_ami->execute();

            // Copy the prebuilt Terraform config to the class directory
            $source_config_path = "/path/to/prebuilt/terraform/$ami_terraform_config";
            $dest_config_path = "$terraform_subdir/$ami_terraform_config";
            if (!copy($source_config_path, $dest_config_path)) {
                die("Failed to copy Terraform config: $source_config_path to $dest_config_path");
            }
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
