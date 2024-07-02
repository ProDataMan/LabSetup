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
    <form action="edit_class.php" method="post">
        <label for="class">Select Class:</label>
        <select name="class" id="class" onchange="this.form.submit()">
            <option value="">Select a class</option>
            <?php
            $conn = new mysqli('localhost', 'instructor', 'password', 'aws_instructor');
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $result = $conn->query("SELECT id, class_name FROM classes");
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['id']}'" . (isset($_POST['class']) && $_POST['class'] == $row['id'] ? " selected" : "") . ">{$row['class_name']}</option>";
            }
            $conn->close();
            ?>
        </select>
    </form>
    
    <?php
    if (isset($_POST['class'])) {
        $class_id = $_POST['class'];
        $conn = new mysqli('localhost', 'instructor', 'password', 'aws_instructor');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $class_result = $conn->query("SELECT * FROM classes WHERE id=$class_id");
        $class = $class_result->fetch_assoc();

        echo "<h2>Edit {$class['class_name']}</h2>";
        echo "<form action='update_class.php' method='post'>";
        echo "<input type='hidden' name='class_id' value='$class_id'>";
        echo "<label for='class_name'>Class Name:</label>";
        echo "<input type='text' id='class_name' name='class_name' value='{$class['class_name']}' required>";
        echo "<br><br>";
        echo "<label for='num_instances'>Number of Instances:</label>";
        echo "<input type='number' id='num_instances' name='num_instances' value='{$class['num_instances']}' min='1' required>";
        echo "<br><br>";
        echo "<label for='ami_details'>AMIs and Default Name Tags:</label>";
        echo "<div id='ami_details'>";
        
        $ami_result = $conn->query("SELECT * FROM amis WHERE class_id=$class_id");
        while ($ami = $ami_result->fetch_assoc()) {
            echo "<div>";
            echo "<input type='hidden' name='ami_ids[]' value='{$ami['id']}'>";
            echo "<input type='text' name='ami_ami_ids[]' placeholder='AMI ID' value='{$ami['ami_id']}' required>";
            echo "<input type='text' name='ami_tags[]' placeholder='Default Name Tag' value='{$ami['ami_tag']}' required>";
            echo "<button type='button' onclick='removeAmi(this)'>Remove</button>";
            echo "</div>";
        }
        
        echo "</div>";
        echo "<button type='button' onclick='addAmiInput()'>Add Another AMI</button>";
        echo "<br><br>";
        echo "<input type='submit' value='Update Class'>";
        echo "</form>";
        
        $conn->close();
    }
    ?>

    <script>
        function addAmiInput() {
            const div = document.createElement('div');
            div.innerHTML = `<input type='hidden' name='ami_ids[]' value=''>
                             <input type='text' name='ami_ami_ids[]' placeholder='AMI ID' required>
                             <input type='text' name='ami_tags[]' placeholder='Default Name Tag' required>
                             <button type='button' onclick='removeAmi(this)'>Remove</button>`;
            document.getElementById('ami_details').appendChild(div);
        }
        
        function removeAmi(button) {
            button.parentElement.remove();
        }
    </script>
</body>
</html>
