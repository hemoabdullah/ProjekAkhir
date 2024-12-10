<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login System</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-sm navbar-secondary bg-secondary sticky-top py-0 mb-3">
        <a class="navbar-brand ms-4" href="index.php">
            <img src="img/brand1.png" style="width: 182px; height: 29px;" alt="Brand Logo">
        </a>
    </nav>

    <div class="ms-5">
        <h2><span class="badge bg-info badge-lg"></span></h2>
    </div>

    <div class="d-block justify-content-center mt-5">
        <h2 class="text-center my-0"><strong>Welcome!</strong></h2>
        <h3 class="text-center">Please enter your account details!</h3>
    </div>

    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-danger text-center" role="alert">
            <?php if($_GET['error'] == 1): ?>
                Database error occurred.
            <?php elseif($_GET['error'] == 2): ?>
                Invalid email or password.
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-center">
        <div class="card" style="width: 600px;">
            <img class="card-img-top mx-auto d-block mt-4" src="img/brand1.png" style="width: 44%; height: 30%;" alt="Title">
            <div class="card-body">
                <form action="login.php" method="POST" onsubmit="handleSubmit(event)">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input
                            type="email"
                            class="form-control"
                            name="email"
                            id="email"
                            required
                            aria-label="Email address"
                            placeholder="username or email"
                        />
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="password" class="form-label">Password</label>
                        <input
                            type="password"
                            class="form-control"
                            name="password"
                            id="password"
                            required
                            aria-label="Password"
                            placeholder="password"
                        />
                        <button type="button" class="btn btn-sm btn-outline-secondary position-absolute top-50 end-0 translate-middle-y me-2" onclick="togglePasswordVisibility()">Show</button>
                    </div>
                    <a class="text-secondary float-end" href="forgotPass.php">Forgot password?</a>

                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" name="remember" id="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100" id="submitButton">Login</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const toggleButton = event.target;
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleButton.innerText = 'Hide';
            } else {
                passwordInput.type = 'password';
                toggleButton.innerText = 'Show';
            }
        }

        function handleSubmit(event) {
            const submitButton = document.getElementById('submitButton');
            submitButton.disabled = true;
            submitButton.innerText = 'Processing...';
        }
    </script>
</body>
</html>
