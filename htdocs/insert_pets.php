<?php  
include("navigation.php");
include("database.php");

// Initialize variables for form data
$species = $breed = $name_of_pets = $date_of_birth = $clientsID = '';
$insert_success = null;
$image_name = '';

// Insert pet code: only process if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data safely
    $species = $_POST['species'];
    $breed = $_POST['breed'];
    $name_of_pets = $_POST['name_of_pets'];
    $date_of_birth = $_POST['date_of_birth'];
    $clientsID = $_POST['clientsID'];

    // Image upload processing
    if (isset($_FILES['pet_image']) && $_FILES['pet_image']['error'] === 0) {
        $image_name = $_FILES['pet_image']['name'];
        $image_tmp_name = $_FILES['pet_image']['tmp_name'];
        $image_size = $_FILES['pet_image']['size'];
        $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);
        $image_extension = strtolower($image_extension);

        // Allowed image extensions
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($image_extension, $allowed_extensions)) {
            $new_image_name = uniqid('', true) . '.' . $image_extension;
            $image_upload_path = 'uploads/' . $new_image_name;

            // Move the image to the uploads folder
            move_uploaded_file($image_tmp_name, $image_upload_path);
            $image_name = $new_image_name;  // Store the file name
        } else {
            $error_message = "You can only upload images (jpg, jpeg, png, gif).";
        }
    } elseif (isset($_FILES['pet_image']) && $_FILES['pet_image']['error'] !== 0) {
        $error_message = "There was an error uploading the image.";
    }

    // Check for empty fields (optional validation)
    if (empty($species) || empty($breed) || empty($name_of_pets) || empty($date_of_birth) || empty($clientsID)) {
        $error_message = "All fields are required!";
    } else {
        // Prepare SQL query to check for duplicates (pet name)
        $check_sql = "SELECT petID FROM Pets WHERE name_of_pets = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("s", $name_of_pets);
        $stmt->execute();
        $check_result = $stmt->get_result();

        if ($check_result->num_rows > 0) {
            // Pet already exists
            $error_message = "Error: A pet with this name already exists.";
        } else {
            // Proceed with insertion if no duplicates are found
            $insert_sql = "INSERT INTO Pets (species, breed, name_of_pets, date_of_birth, clientsID, image_name) 
                           VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("ssssss", $species, $breed, $name_of_pets, $date_of_birth, $clientsID, $image_name);

            if ($stmt->execute()) {
                // Successfully inserted
                $insert_success = true;
                // Redirect after successful insertion (optional)
                header("Location: pets.php?insert_success=1");
                exit();
            } else {
                // Failed to insert
                $insert_success = false;
                $error_message = "Error: " . $stmt->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Pet</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="nav-container">
        <a href="pets.php" class="nav-link">Pet List</a>
        <a href="insert_pets.php" class="nav-link">Insert Pet</a>
    </div>

    <h2>Insert New Pet</h2>

    <?php if (isset($error_message)) { ?>
        <div class="error-message"><?php echo $error_message; ?></div>
    <?php } ?>

    <?php if ($insert_success === true) { ?>
        <div class="success-message">New pet added successfully!</div>
    <?php } elseif ($insert_success === false) { ?>
        <div class="error-message">Failed to add new pet.</div>
    <?php } ?>

    <!-- Form for inserting a pet -->
    <form action="insert_pets.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="species">Species:</label>
            <input type="text" id="species" name="species" required>
        </div>
        <div class="form-group">
            <label for="breed">Breed:</label>
            <input type="text" id="breed" name="breed" required>
        </div>
        <div class="form-group">
            <label for="name_of_pets">Pet Name:</label>
            <input type="text" id="name_of_pets" name="name_of_pets" required>
        </div>
        <div class="form-group">
            <label for="date_of_birth">Date of Birth:</label>
            <input type="date" id="date_of_birth" name="date_of_birth" required>
        </div>
        <div class="form-group">
            <label for="clientsID">Client ID:</label>
            <input type="number" id="clientsID" name="clientsID" required>
        </div>
        <div class="form-group">
            <label for="pet_image">Pet Image:</label>
            <input type="file" id="pet_image" name="pet_image" accept="image/*">
        </div>

        <button type="submit" class="submit-btn">Insert Pet</button>
    </form>
</body>
</html>
