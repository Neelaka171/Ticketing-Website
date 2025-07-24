<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="..\Style\registerStyle.css">
</head>
<body>
<div class="background">
    <div class="shape"></div>
    <div class="shape"></div>
</div>
<div class="register-container">
    <div class="header">
        <h2>Create Your Account</h2>
        <p>Join our community today</p>
    </div>
    
    <?php
    require_once '..\DBconnection\database.php';
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Initialize variables
        $username = $name = $email = $password = $confirm_password = '';
        $errors = array();
        
        // Sanitize and validate inputs
        $username = trim($_POST["username"]);
        if (empty($username)) {
            $errors[] = "Username is required";
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            $errors[] = "Username can only contain letters, numbers, and underscores";
        } elseif (strlen($username) < 4) {
            $errors[] = "Username must be at least 4 characters";
        } else {
            // Check if username already exists
            $sql = "SELECT id FROM users WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();
            
            if ($stmt->num_rows > 0) {
                $errors[] = "Username is already taken";
            }
            $stmt->close();
        }
        
        $name = trim($_POST["name"]);
        if (empty($name)) {
            $errors[] = "Full name is required";
        }
        
        $email = trim($_POST["email"]);
        if (empty($email)) {
            $errors[] = "Email is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        } else {
            // Check if email already exists
            $sql = "SELECT id FROM users WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            
            if ($stmt->num_rows > 0) {
                $errors[] = "Email is already registered";
            }
            $stmt->close();
        }
        
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];
        if (empty($password)) {
            $errors[] = "Password is required";
        } elseif (strlen($password) < 6) {
            $errors[] = "Password must be at least 6 characters";
        } elseif ($password != $confirm_password) {
            $errors[] = "Passwords do not match";
        }
        
        // If no errors, proceed with registration
        if (empty($errors)) {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Prepare and bind
            $sql = "INSERT INTO users (username, name, email, password) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $username, $name, $email, $hashed_password);
            
            // Execute the statement
            if ($stmt->execute()) {
                echo "<div class='success-message'>";
                echo "<i class='fas fa-check-circle'></i>";
                echo "<h3>Registration Successful!</h3>";
                echo "<div class='user-details'>";
               // echo "<p><span>Username:</span> $username</p>";
                //echo "<p><span>Name:</span> $name</p>";
                //echo "<p><span>Email:</span> $email</p>";
                echo "</div>";
                echo "</div>";
                
                // Clear form fields
                $_POST = array();
            } else {
                echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
            }
            $stmt->close();
        } else {
            // Display errors
            echo "<div class='alert alert-danger'>";
            //echo "<h4>Please fix the following errors:</h4>";
            echo "<ul>";
            foreach ($errors as $error) {
                echo "<li>$error</li>";
            }
            echo "</ul>";
            echo "</div>";
        }
    }
    
    // Close connection
    $conn->close();
    ?>
    
    <form method="POST" action="" id="registerForm">
        <div class="input-group">
            <i class="fas fa-user"></i>
            <input type="text" name="username" id="username" placeholder="Username" 
                   value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
            <div class="error-message" id="username-error">Username must be at least 4 characters</div>
        </div>
        
        <div class="input-group">
            <i class="fas fa-id-card"></i>
            <input type="text" name="name" id="name" placeholder="Full Name" 
                   value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required>
        </div>
        
        <div class="input-group">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" id="email" placeholder="Email Address" 
                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
            <div class="error-message" id="email-error">Please enter a valid email address</div>
        </div>
        
        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" id="password" placeholder="Password" required>
            <i class="fas fa-eye toggle-password"></i>
            <div class="error-message" id="password-error">Password must be at least 6 characters</div>
        </div>
        
        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
            <i class="fas fa-eye toggle-password"></i>
            <div class="error-message" id="confirm-error">Passwords do not match</div>
        </div>
        
        <button type="submit" class="register-btn">
            <span>Register Now</span>
            <i class="fas fa-arrow-right"></i>
        </button>
        
        <div class="social-login">
            <p>Or sign up with</p>
            <div class="social-icons">
                <a href="#"><i class="fab fa-google"></i></a>
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
            </div>
        </div>
        
        <p class="login-link">Already have an account? <a href="login.php">Login here</a></p>
    </form>
</div>

<script>
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(icon => {
        icon.addEventListener('click', function() {
            const passwordInput = this.parentElement.querySelector('input');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    });

    // Form validation
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        let isValid = true;
        
        // Validate username
        const username = document.getElementById('username');
        const usernameError = document.getElementById('username-error');
        if (username.value.length < 4) {
            usernameError.style.display = 'block';
            isValid = false;
        } else {
            usernameError.style.display = 'none';
        }
        
        // Validate email
        const email = document.getElementById('email');
        const emailError = document.getElementById('email-error');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email.value)) {
            emailError.style.display = 'block';
            isValid = false;
        } else {
            emailError.style.display = 'none';
        }
        
        // Validate password
        const password = document.getElementById('password');
        const passwordError = document.getElementById('password-error');
        if (password.value.length < 6) {
            passwordError.style.display = 'block';
            isValid = false;
        } else {
            passwordError.style.display = 'none';
        }
        
        // Validate confirm password
        const confirmPassword = document.getElementById('confirm_password');
        const confirmError = document.getElementById('confirm-error');
        if (password.value !== confirmPassword.value) {
            confirmError.style.display = 'block';
            isValid = false;
        } else {
            confirmError.style.display = 'none';
        }
        
        if (!isValid) {
            e.preventDefault();
        }
    });

    // Real-time validation
    document.getElementById('username').addEventListener('input', function() {
        const usernameError = document.getElementById('username-error');
        if (this.value.length >= 4) {
            usernameError.style.display = 'none';
        }
    });
    
    document.getElementById('email').addEventListener('input', function() {
        const emailError = document.getElementById('email-error');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (emailRegex.test(this.value)) {
            emailError.style.display = 'none';
        }
    });
    
    document.getElementById('password').addEventListener('input', function() {
        const passwordError = document.getElementById('password-error');
        if (this.value.length >= 6) {
            passwordError.style.display = 'none';
        }
    });
    
    document.getElementById('confirm_password').addEventListener('input', function() {
        const confirmError = document.getElementById('confirm-error');
        if (this.value === document.getElementById('password').value) {
            confirmError.style.display = 'none';
        }
    });
</script>
</body>
</html>