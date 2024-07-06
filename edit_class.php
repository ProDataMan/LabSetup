<!-- /var/www/html/edit_class.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Class</title>
</head>
<body>
    <?php include 'navigation.php'; ?>
    <h1>Edit Class</h1>
    <form method="post" action="edit_class.php">
        <label for="class_id">Select Class:</label>
        <select name="class_id" id="class_id" onchange="this.form.submit()">
            <option value="">Select a class</option>
            <?php
            $conn = new mysqli('localhost', 'instructor', 'password', 'aws_instructor');
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $result = $conn->query("SELECT id, class_name FROM classes");
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['id']}'";
                if ((isset($_GET['class_id']) && $_GET['class_id'] == $row['id']) || (isset($_POST['class_id']) && $_POST['class_id'] == $row['id'])) {
                    echo " selected";
                }
                echo ">" . htmlspecialchars($row['class_name'], ENT_QUOTES, 'UTF-8') . "</option>";
            }
            $conn->close();
            ?>
        </select>
    </form>

    <?php
    if ((isset($_GET['class_id']) && !empty($_GET['class_id'])) || (isset($_POST['class_id']) && !empty($_POST['class_id']))) {
        $class_id = isset($_GET['class_id']) ? $_GET['class_id'] : $_POST['class_id'];
        $conn = new mysqli('localhost', 'instructor', 'password', 'aws_instructor');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $class_result = $conn->query("SELECT * FROM classes WHERE id=$class_id");
        $class = $class_result->fetch_assoc();

        $ami_result = $conn->query("SELECT * FROM amis WHERE class_id=$class_id");
        $amis = [];
        while ($row = $ami_result->fetch_assoc()) {
            $amis[] = $row;
        }
        $conn->close();
    ?>

    <form method="post" action="update_class.php">
        <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
        <label for="class_name">Class Name:</label>
        <input type="text" id="class_name" name="class_name" value="<?php echo htmlspecialchars($class['class_name'], ENT_QUOTES, 'UTF-8'); ?>" required>
        <br><br>
        <label for="num_instances">Number of Instances:</label>
        <input type="number" id="num_instances" name="num_instances" value="<?php echo $class['num_instances']; ?>" min="1" required>
        <br><br>
        <label for="terraform_config_path">Terraform Config Path:</label>
        <input type="text" id="terraform_config_path" name="terraform_config_path" value="<?php echo htmlspecialchars($class['terraform_config_path'], ENT_QUOTES, 'UTF-8'); ?>" required>
        <br><br>
        <label for="ami_details">AMIs and Default Name Tags:</label>
        <div id="ami_details">
            <?php foreach ($amis as $ami) { ?>
            <div>
