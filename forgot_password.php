<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Forgot Password</title>
	<link rel="stylesheet" href="assets/css/edu_hub_reg.css">
</head>

<body>
<div class="auth-container">
    <div class="auth-header">
		<button class="close-btn" onclick="window.location.href='edu_hub_registration.php'">&times;</button>
        <h1 class="auth-title">Forgot your password?</h1>
        <p class="auth-subtitle">Enter your email and we'll send you a link to reset it.</p>
    </div>

    <div class="auth-content">
        <form action="process_password_reset.php" method="post">
            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <input class="form-input" id="email" type="email" name="email" placeholder="Enter your email" required>
            </div>

            <button type="submit" name="request_reset" class="submit-btn">Send reset link</button>

            <a href="edu_hub_registration.php" class="auth-link">Back to log in</a>
        </form>
    </div>
</div>

	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script>
		const urlParams = new URLSearchParams(window.location.search);
		const status = urlParams.get('status');
		const message = urlParams.get('message');

		if (status === 'sent') {
			Swal.fire({
				title: 'Check your email',
				text: message || "If that account exists, a password reset link is on its way.",
				icon: 'success',
				confirmButtonColor: '#38b6ff'
			});
		} else if (status === 'error') {
			Swal.fire({
				title: 'Error',
				text: message ? decodeURIComponent(message.replace(/\+/g, ' ')) : 'There was a problem. Please try again.',
				icon: 'error',
				confirmButtonColor: '#ef4444'
			});
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
	</script>
</body>
</html>
