<?php  
include("navigation.php");
include("database.php");
 ?>
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Table</title>
    <style>
        /* Styles here (no changes needed) */
    </style>
</head>
<body>
    <div class="nav-container">
        <a href="employees.php" class="nav-link">Employee List</a>
        <a href="insert_employee.php" class="nav-link">Insert Employee</a>
    </div>

    <!-- Employee Insert Form -->
    <h2>Insert New Employee</h2>
    <form action="employees.php" method="POST">
        <label for="employeesID">Employee ID:</label><br>
        <input type="text" id="employeesID" name="employeesID" required><br><br>

        <label for="full_name">Full Name:</label><br>
        <input type="text" id="full_name" name="full_name" required><br><br>

        <label for="date_of_birth">Date of Birth:</label><br>
        <input type="date" id="date_of_birth" name="date_of_birth" required><br><br>

        <label for="name_of_school">Name of School:</label><br>
        <input type="text" id="name_of_school" name="name_of_school"><br><br>

        <label for="employeesType">Employee Type:</label><br>
        <input type="text" id="employeesType" name="employeesType" required><br><br>

        <input type="submit" value="Insert Employee">
    </form>

    <!-- Employee Table -->
    <table>
        <tr>
            <th>Employee ID</th>
            <th>Full Name</th>
            <th>Date of Birth</th>
            <th>Name of School</th>
            <th>Employee Type</th>
            <th>Actions</th>
        </tr>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["employeesID"] . "</td>";
                echo "<td>" . $row["full_name"] . "</td>";
                echo "<td>" . $row["date_of_birth"] . "</td>";
                echo "<td>" . $row["name_of_school"] . "</td>";
                echo "<td>" . $row["employeesType"] . "</td>";
                echo "<td><button class='delete-btn' onclick='deleteEmployee(" . $row["employeesID"] . ")'>Delete</button></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No records found</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>
