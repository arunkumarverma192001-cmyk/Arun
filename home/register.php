<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    body {
      background: linear-gradient(to right, #4facfe, #00f2fe);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .register-card {
      background: white;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      padding: 30px;
      width: 100%;
      max-width: 450px;
    }
    .register-card h2 {
      text-align: center;
      margin-bottom: 20px;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="register-card">
    <h2>Register</h2>
    <form id="registerForm">
      <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Phone</label>
        <input type="number" name="phone" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Address</label>
        <input type="text" name="address" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <div class="d-grid">
        <button type="submit" class="btn btn-primary">Register</button>
      </div>
    </form>
  </div>

<script>
$(document).ready(function(){
    $("#registerForm").on("submit", function(e){
        e.preventDefault();
        $.ajax({
            url: "./insert-users.php", // path check करें
            type: "POST",
            data: $(this).serialize(),
            success: function(response){
                response = response.trim();
                if(response === "success"){
                    alert("Registration successful!");
                    $("#registerForm")[0].reset();
                }
                else if(response === "exists"){
                    alert("Email already registered.");
                }
                else{
                    alert("Error: " + response);
                }
            },
            error: function(xhr, status, error){
                alert("AJAX Error: " + error);
            }
        });
    });
});
</script>
</body>
</html>
