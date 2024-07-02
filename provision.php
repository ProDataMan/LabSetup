<!-- /var/www/html/provision.php -->
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $class_id = $_POST['class'];
    $num_students = $_POST['students'];

    // Connect to the database
    $conn = new mysqli('localhost', 'instructor', 'password', 'aws_instructor');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch class details
    $class_result = $conn->query("SELECT * FROM classes WHERE id=$class_id");
    $class = $class_result->fetch_assoc();
    $num_instances = $class['num_instances'];

    // Fetch AMIs for the class
    $ami_result = $conn->query("SELECT ami_id FROM amis WHERE class_id=$class_id");
    $amis = [];
    while ($row = $ami_result->fetch_assoc()) {
        $amis[] = $row['ami_id'];
    }
    $conn->close();

    // Create Terraform configuration
    $tf_config = "provider \"aws\" {
        region = \"us-west-2\"
    }

    resource \"aws_instance\" \"example\" {";

    for ($i = 0; $i < $num_instances; $i++) {
        $ami_id = $amis[$i % count($amis)];
        $tf_config .= "
        instance_type = \"t2.micro\"
        ami           = \"$ami_id\"
        count         = $num_students
        ";
    }

    $tf_config .= "}";

    // Write the Terraform configuration to a file
    $tf_file = "/tmp/terraform_".uniqid().".tf";
    file_put_contents($tf_file, $tf_config);

    // Execute Terraform commands
    exec("terraform init", $output, $retval);
    if ($retval == 0) {
        exec("terraform apply -auto-approve", $output, $retval);
        if ($retval == 0) {
            echo "Instances provisioned successfully.";
        } else {
            echo "Error applying Terraform configuration.";
        }
    } else {
        echo "Error initializing Terraform.";
    }

    // Clean up
    unlink($tf_file);
} else {
    echo "Invalid request.";
}
?>
