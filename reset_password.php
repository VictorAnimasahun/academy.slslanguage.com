<?php
require_once __DIR__ . '/bootstrap.php';

$token = trim($_GET['token'] ?? '');
$tokenValid = false;

if ($token !== '') {
    $stmt = $db->prepare("SELECT id FROM students WHERE password_reset_token = ? AND password_reset_token_created_at > NOW() - INTERVAL 1 HOUR");
    $stmt->execute([$token]);
    $tokenValid = (bool) $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Reset Password</title>
	<link rel="stylesheet" href="assets/css/edu_hub_reg.css">
</head>

<body>
<div class="auth-container">
    <div class="auth-header">
		<button class="close-btn" onclick="window.location.href='edu_hub_registration.php'">&times;</button>
        <h1 class="auth-title">Reset your password</h1>
        <p class="auth-subtitle"><?php echo $tokenValid ? 'Choose a new password below.' : "This link has expired or is invalid."; ?></p>
    </div>

    <div class="auth-content">
        <?php if ($tokenValid): ?>
        <form action="process_password_reset.php" method="post">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

            <div class="form-group">
                <label class="form-label" for="password">New Password</label>
                <div class="password-field">
                    <input class="form-input form-input-password" id="password" type="password" name="password" placeholder="At least 8 characters" required>
                    <button type="button" class="toggle-password-btn" onclick="togglePasswordVisibility(this)" aria-label="Show password" aria-pressed="false">
                        <svg class="icon-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1.5 12S5 5 12 5s10.5 7 10.5 7-3.5 7-10.5 7S1.5 12 1.5 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg class="icon-eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><path d="M17.94 17.94A10.5 10.5 0 0 1 12 19c-7 0-10.5-7-10.5-7a18.6 18.6 0 0 1 4.22-5.06M9.9 4.24A9.1 9.1 0 0 1 12 5c7 0 10.5 7 10.5 7a18.4 18.4 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="confirmpassword">Confirm New Password</label>
                <div class="password-field">
                    <input class="form-input form-input-password" id="confirmpassword" type="password" name="confirmpassword" placeholder="Re-enter your new password" required>
                    <button type="button" class="toggle-password-btn" onclick="togglePasswordVisibility(this)" aria-label="Show password" aria-pressed="false">
                        <svg class="icon-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1.5 12S5 5 12 5s10.5 7 10.5 7-3.5 7-10.5 7S1.5 12 1.5 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg class="icon-eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><path d="M17.94 17.94A10.5 10.5 0 0 1 12 19c-7 0-10.5-7-10.5-7a18.6 18.6 0 0 1 4.22-5.06M9.9 4.24A9.1 9.1 0 0 1 12 5c7 0 10.5 7 10.5 7a18.4 18.4 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    </button>
                </div>
            </div>

            <button type="submit" name="reset_password" class="submit-btn">Reset password</button>
        </form>
        <?php else: ?>
        <a href="forgot_password.php" class="auth-link">Request a new link</a>
        <?php endif; ?>
    </div>
</div>

	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script>
		const urlParams = new URLSearchParams(window.location.search);
		const status = urlParams.get('status');
		const message = urlParams.get('message');

		if (status === 'error') {
			Swal.fire({
				title: 'Error',
				text: message ? decodeURIComponent(message.replace(/\+/g, ' ')) : 'There was a problem. Please try again.',
				icon: 'error',
				confirmButtonColor: '#ef4444'
			});
		}

		function togglePasswordVisibility(btn) {
			const input = btn.previousElementSibling;
			const showing = input.type === 'text';
			input.type = showing ? 'password' : 'text';
			btn.querySelector('.icon-eye').style.display = showing ? '' : 'none';
			btn.querySelector('.icon-eye-off').style.display = showing ? 'none' : '';
			btn.setAttribute('aria-label', showing ? 'Show password' : 'Hide password');
			btn.setAttribute('aria-pressed', showing ? 'false' : 'true');
		}

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
