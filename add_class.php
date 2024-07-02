<!-- /var/www/html/add_class.html -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Class</title>
</head>
<body>
    <?php include 'navigation.php'; ?>
    <h1>Add New Class</h1>
    <form action="add_class.php" method="post">
        <label for="class_name">Class Name:</label>
        <input type="text" id="class_name" name="class_name" required>
        <br><br>
        <label for="num_instances">Number of Instances:</label>
        <input type="number" id="num_instances" name="num_instances" min="1" required>
        <br><br>
        <label for="ami_details">AMIs and Default Name Tags:</label>
        <div id="ami_details">
            <div>
                <input type="text" name="ami_ids[]" placeholder="AMI ID" required>
                <input type="text" name="ami_tags[]" placeholder="Default Name Tag" required>
            </div>
        </div>
        <button type="button" onclick="addAmiInput()">Add Another AMI</button>
        <br><br>
        <input type="submit" value="Add Class">
    </form>

    <script>
        function addAmiInput() {
            const div = document.createElement('div');
            div.innerHTML = `<input type="text" name="ami_ids[]" placeholder="AMI ID" required>
                             <input type="text" name="ami_tags[]" placeholder="Default Name Tag" required>`;
            document.getElementById('ami_details').appendChild(div);
        }
    </script>
</body>
</html>
