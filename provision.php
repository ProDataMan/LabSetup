<!-- /var/www/html/provision.php -->
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $class_id = $_POST['class'];
    $num_students = $_POST['students'];

    // Enable error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Connect to the database
    $conn = new mysqli('localhost', 'instructor', 'password', 'aws_instructor');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch class details
    $class_result = $conn->query("SELECT * FROM classes WHERE id=$class_id");
    if (!$class_result) {
        die("Error fetching class details: " . $conn->error);
    }
    $class = $class_result->fetch_assoc();
    $num_instances = $class['num_instances'];

    // Fetch AMIs for the class
    $ami_result = $conn->query("SELECT ami_id, ami_tag FROM amis WHERE class_id=$class_id");
    if (!$ami_result) {
        die("Error fetching AMI details: " . $conn->error);
    }
    $amis = [];
    while ($row = $ami_result->fetch_assoc()) {
        $amis[] = $row;
    }
    $conn->close();

    // Create Terraform configuration
    $tf_config = "provider \"aws\" {
        region = \"us-west-2\"
    }\n\n";

    for ($i = 0; $i < $num_students; $i++) {
        foreach ($amis as $index => $ami) {
            $ami_id = $ami['ami_id'];
            $ami_tag = str_replace('#', $i + 1, $ami['ami_tag']);
            $tf_config .= "resource \"aws_instance\" \"example_{$i}_{$index}\" {
        instance_type = \"t2.micro\"
        ami           = \"$ami_id\"
        tags = {
            Name = \"$ami_tag\"
        }
    }\n\n";
        }
    }

    // Create a temporary directory for the Terraform configuration
    $tf_dir = "/tmp/terraform_" . uniqid();
    if (!mkdir($tf_dir)) {
        die("Failed to create temporary directory: $tf_dir");
    }

    // Write the Terraform configuration to a file
    $tf_file = $tf_dir . "/main.tf";
    if (file_put_contents($tf_file, $tf_config) === false) {
        die("Failed to write Terraform configuration to file: $tf_file");
    }

    // Change the working directory to the temporary directory
    if (!chdir($tf_dir)) {
        die("Failed to change directory to: $tf_dir");
    }

    // Execute Terraform commands
    exec("terraform init 2>&1", $init_output, $init_retval);
    if ($init_retval == 0) {
        exec("terraform apply -auto-approve 2>&1", $apply_output, $apply_retval);
        if ($apply_retval == 0) {
            echo "Instances provisioned successfully.";
        } else {
            echo "Error applying Terraform configuration: " . implode("\n", $apply_output);
        }
    } else {
        echo "Error initializing Terraform: " . implode("\n", $init_output);
    }

    // Clean up
    array_map('unlink', glob("$tf_dir/*.*"));
    rmdir($tf_dir);
} else {
    echo "Invalid request.";
}
?>
