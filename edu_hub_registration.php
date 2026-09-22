<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login & Registration</title>
	<link rel="stylesheet" href="assets/css/edu_hub_reg.css">
</head>

<body>
<?php if (isset($_GET['message'])): ?>
<div class="alert">
    <?php echo htmlspecialchars($_GET['message']); ?>
</div>
<?php endif; ?>

<?php 
$showRegister = isset($_GET['form']) && $_GET['form'] === 'register';
?>

<div class="auth-container">
    <div class="auth-header">
		<button class="close-btn" onclick="goHome()">&times;</button>
        <h1 class="auth-title"><?php echo $showRegister ? 'Create Account' : 'Welcome Back'; ?></h1>
        <p class="auth-subtitle">
            <?php echo $showRegister ? 'Fill in the details below to create your account' : 'Please sign in to your account'; ?>
        </p>
        
        <!-- Tab Navigation -->
        <div class="tab-nav">
            <a href="?" class="tab-btn <?php echo !$showRegister ? 'active' : ''; ?>">Sign In</a>
            <a href="?form=register" class="tab-btn <?php echo $showRegister ? 'active' : ''; ?>">Sign Up</a>
        </div>
    </div>

    <div class="auth-content">
        <?php if (!$showRegister): ?>
        <!-- Login Form -->
        <form action="process_registration.php" method="post">
            <div class="form-group">
                <label class="form-label" for="login_email">Email Address</label>
                <input class="form-input" id="login_email" type="email" name="email" placeholder="Enter your email" required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="login_password">Password</label>
                <div class="password-field">
                    <input class="form-input form-input-password" id="login_password" type="password" name="password" placeholder="Enter your password" required>
                    <button type="button" class="toggle-password-btn" onclick="togglePasswordVisibility(this)" aria-label="Show password" aria-pressed="false">
                        <svg class="icon-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1.5 12S5 5 12 5s10.5 7 10.5 7-3.5 7-10.5 7S1.5 12 1.5 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg class="icon-eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><path d="M17.94 17.94A10.5 10.5 0 0 1 12 19c-7 0-10.5-7-10.5-7a18.6 18.6 0 0 1 4.22-5.06M9.9 4.24A9.1 9.1 0 0 1 12 5c7 0 10.5 7 10.5 7a18.4 18.4 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    </button>
                </div>
            </div>
            
            <button type="submit" name="login" class="submit-btn">Sign In</button>
            
            <a href="?form=register" class="auth-link">Don't have an account? Sign up here</a>
        </form>
        
        <?php else: ?>
        <!-- Registration Form -->
        <form action="process_registration.php" method="post">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="firstname">First Name</label>
                    <input class="form-input" id="firstname" type="text" name="firstname" placeholder="First name" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="lastname">Last Name</label>
                    <input class="form-input" id="lastname" type="text" name="lastname" placeholder="Last name" required>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <input class="form-input" id="email" type="email" name="email" placeholder="Enter your email" required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="phonenumber">Phone Number</label>
                <input class="form-input" id="phonenumber" type="text" name="phonenumber" placeholder="Your phone number" required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <div class="password-field">
                    <input class="form-input form-input-password" id="password" type="password" name="password" placeholder="Create a password" required>
                    <button type="button" class="toggle-password-btn" onclick="togglePasswordVisibility(this)" aria-label="Show password" aria-pressed="false">
                        <svg class="icon-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1.5 12S5 5 12 5s10.5 7 10.5 7-3.5 7-10.5 7S1.5 12 1.5 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg class="icon-eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><path d="M17.94 17.94A10.5 10.5 0 0 1 12 19c-7 0-10.5-7-10.5-7a18.6 18.6 0 0 1 4.22-5.06M9.9 4.24A9.1 9.1 0 0 1 12 5c7 0 10.5 7 10.5 7a18.4 18.4 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="confirmpassword">Confirm Password</label>
                <div class="password-field">
                    <input class="form-input form-input-password" id="confirmpassword" type="password" name="confirmpassword" placeholder="Confirm your password" required>
                    <button type="button" class="toggle-password-btn" onclick="togglePasswordVisibility(this)" aria-label="Show password" aria-pressed="false">
                        <svg class="icon-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1.5 12S5 5 12 5s10.5 7 10.5 7-3.5 7-10.5 7S1.5 12 1.5 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg class="icon-eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><path d="M17.94 17.94A10.5 10.5 0 0 1 12 19c-7 0-10.5-7-10.5-7a18.6 18.6 0 0 1 4.22-5.06M9.9 4.24A9.1 9.1 0 0 1 12 5c7 0 10.5 7 10.5 7a18.4 18.4 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    </button>
                </div>
            </div>
            
            <button type="submit" name="create" class="submit-btn register">Create Account</button>
            
            <a href="?" class="auth-link">Already have an account? Sign in here</a>
        </form>
        <?php endif; ?>
    </div>
</div>

	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script>
		const urlParams = new URLSearchParams(window.location.search);
		const status = urlParams.get('status');
		const message = urlParams.get('message');
		
		if (status === 'success') {
			// Check if this is email verification or registration
			if (message && message.includes('verified')) {
				Swal.fire({
					title: 'Email Verified!',
					text: 'Your email has been verified successfully. You can now log in to your account.',
					icon: 'success',
					confirmButtonColor: '#38b6ff',
					confirmButtonText: 'Log In Now'
				}).then((result) => {
					if (result.isConfirmed) {
						// Switch to login tab
						window.location.href = '?';
					}
				});
			} else {
				// Registration success
				Swal.fire({
					title: 'Registration Successful!',
					html: '<p>Please check your email inbox (and spam folder) for a verification link.</p><p style="color: #666; font-size: 14px; margin-top: 10px;">📧 You must verify your email before logging in.</p>',
					icon: 'success',
					confirmButtonColor: '#38b6ff',
					confirmButtonText: 'Got it!'
				});
			}
		} else if (status === 'error') {
			const errorMessage = message ? decodeURIComponent(message.replace(/\+/g, ' ')) : 'There was a problem. Please try again.';
			Swal.fire({
				title: 'Error!',
				text: errorMessage,
				icon: 'error',
				confirmButtonColor: '#ef4444'
			});
		} else if (status === 'warning') {
			const warningMessage = message ? decodeURIComponent(message.replace(/\+/g, ' ')) : 'Account created but verification email failed to send.';
			Swal.fire({
				title: 'Warning!',
				text: warningMessage,
				icon: 'warning',
				confirmButtonColor: '#f59e0b'
			});
		} else if (status === 'login_success') {
			Swal.fire({
				title: 'Welcome Back!',
				text: 'You have successfully logged in.',
				icon: 'success',
				timer: 2000,
				showConfirmButton: false
			}).then(() => {
				// Redirect to dashboard
				window.location.href = 'learning_dashboard.php';
			});
		} else if (status === 'login_error') {
			const loginError = message ? decodeURIComponent(message.replace(/\+/g, ' ')) : 'Invalid email or password.';
			Swal.fire({
				title: 'Login Failed',
				text: loginError,
				icon: 'error',
				confirmButtonColor: '#ef4444'
			});
		}

		// Show/hide password — lets a student confirm they typed the right thing
		// before submitting, rather than finding out only after a failed login.
		function togglePasswordVisibility(btn) {
			const input = btn.previousElementSibling;
			const showing = input.type === 'text';
			input.type = showing ? 'password' : 'text';
			btn.querySelector('.icon-eye').style.display = showing ? '' : 'none';
			btn.querySelector('.icon-eye-off').style.display = showing ? 'none' : '';
			btn.setAttribute('aria-label', showing ? 'Show password' : 'Hide password');
			btn.setAttribute('aria-pressed', showing ? 'false' : 'true');
		}

		// Close button functionality
		function goHome() {
			// Detect if we're on localhost
			const isLocal = window.location.hostname === 'localhost' || 
			                window.location.hostname === '127.0.0.1';
			
			if (isLocal) {
				window.location.href = '/slslanguage.com/';
			} else {
				window.location.href = '/';
			}
		}

		// Enhanced form validation
		document.querySelectorAll('.form-input').forEach(input => {
			input.addEventListener('blur', function() {
				if (this.checkValidity()) {
					this.classList.remove('error');
					this.classList.add('success');
				} else {
					this.classList.remove('success');
					this.classList.add('error');
				}
			});

			input.addEventListener('input', function() {
				this.classList.remove('error', 'success');
			});
		});

		// Password confirmation validation
		const password = document.getElementById('password');
		const confirmPassword = document.getElementById('confirmpassword');
		
		if (password && confirmPassword) {
			confirmPassword.addEventListener('blur', function() {
				if (password.value !== confirmPassword.value && confirmPassword.value !== '') {
					confirmPassword.classList.add('error');
					confirmPassword.classList.remove('success');
				} else if (confirmPassword.value !== '') {
					confirmPassword.classList.add('success');
					confirmPassword.classList.remove('error');
				}
			});
		}
	</script>
</body>
</html>