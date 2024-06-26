<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "profiles_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    
    // Handle file upload
    $profile_pic = "";
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $target_dir = "images/";
        $profile_pic = $target_dir . basename($_FILES["profile_pic"]["name"]);
        
        // Check if file is uploaded successfully
        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $profile_pic)) {
            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO profiles (name, email, phone, address, profile_pic) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $name, $email, $phone, $address, $profile_pic);
            
            // Execute the statement
            if ($stmt->execute()) {
                // Redirect to profiles.php
                header("Location: profiles.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error uploading file. Please check the permissions of the target directory.";
        }
    } else {
        echo "Error: " . $_FILES['profile_pic']['error'];
    }

    $conn->close();
}
?>