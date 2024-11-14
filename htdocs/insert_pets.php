<?php  
include("navigation.php");
include("database.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Pet</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #C8C6D7;
            color: #4A4063;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            flex-direction: column;
        }

        h2 {
            font-size: 2.5em;
            color: #783F8E;
            margin-bottom: 20px;
            font-family: 'Arial';
        }

        .form-container {
            background-color: #BFACC8;
            padding: 30px;
            border-radius: 10px;
            width: 45%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"], input[type="date"] {
            width: 100%;
            padding: 15px;
            border: 1px solid #4A4063;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .submit-btn {
            background-color: #4F1271;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .submit-btn:hover {
            background-color: #783F8E;
        }

        .nav-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .nav-link {
            margin: 0 15px;
            padding: 10px 20px;
            background-color: #4F1271;
            color: #FFFFFF;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            background-color: #783F8E;
            transform: scale(1.1);
            text-decoration: underline;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="nav-container">
        <a href="pets.php" class="nav-link">Pet List</a>
        <a href="insert_pets.php" class="nav-link">Insert Pet</a>
    </div>

    <div class="form-container">

                <center><h2>Insert New Pet</h2>

        <form action="pets.php" method="POST"></center>
           
            <div class="form-group">
                <label for="species">Species:</label>
                <input type="text" id="full_name" name="species" required>
            </div>

            <div class="form-group">
                <label for="breed">Breed:</label>
                <input type="text" id="breed" name="breed" required>
            </div>

            <div class="form-group">
                <label for="Pet Name">Pet Name:</label>
                <input type="text" id="Pet Name" name="Pet Name" required>
            </div>

            <div class="form-group">
                <label for="date_of_birth">Date of Birth:</label>
                <input type="date" id="date_of_birth" name="date_of_birth" required>
            </div>

            <button type="submit" class="submit-btn">Submit</button>
        </form>
    </div>
</body>
</html>
