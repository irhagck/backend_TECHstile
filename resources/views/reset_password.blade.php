<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #f4f5f7;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }
        .card {
            background: #fff;
            width: 100%;
            max-width: 400px;
            padding: 32px;
            border-radius: 10px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        }
        h1 {
            font-size: 20px;
            margin-bottom: 6px;
            color: #1a1a1a;
        }
        p.subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 24px;
        }
        .field {
            margin-bottom: 18px;
        }
        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #333;
            margin-bottom: 6px;
        }
        input[type="password"] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.15s;
        }
        input[type="password"]:focus {
            border-color: #4f46e5;
        }
        input.error {
            border-color: #dc2626;
        }
        .error-msg {
            color: #dc2626;
            font-size: 12px;
            margin-top: 5px;
            min-height: 14px;
        }
        button {
            width: 100%;
            padding: 11px;
            background: #4f46e5;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 8px;
            transition: background 0.15s;
        }
        button:hover {
            background: #4338ca;
        }
        button:disabled {
            background: #a5a6f6;
            cursor: not-allowed;
        }
        .banner {
            display: none;
            padding: 10px 12px;
            border-radius: 6px;
            font-size: 13px;
            margin-bottom: 16px;
        }
        .banner.success {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
        }
        .banner.error {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }
    </style>
</head>
<body>

    <div class="card">
        <h1>Reset your password</h1>
        <p class="subtitle">Please enter and confirm your new password.</p>

        <div class="banner success" id="successBanner">Password has been reset successfully. You can now log in.</div>
        <div class="banner error" id="errorBanner"></div>

        <form id="passwordForm" novalidate>
            <div class="field">
                <label for="newPassword">New Password</label>
                <input type="password" id="newPassword" name="newPassword" placeholder="Enter new password">
                <div class="error-msg" id="newPasswordError"></div>
            </div>

            <div class="field">
                <label for="confirmPassword">Confirm Password</label>
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Re-enter new password">
                <div class="error-msg" id="confirmPasswordError"></div>
            </div>

            <button type="submit" id="submitBtn">Update Password</button>
        </form>
    </div>

    <script>
        const form = document.getElementById('passwordForm');
        const newPassword = document.getElementById('newPassword');
        const confirmPassword = document.getElementById('confirmPassword');
        const newPasswordError = document.getElementById('newPasswordError');
        const confirmPasswordError = document.getElementById('confirmPasswordError');
        const successBanner = document.getElementById('successBanner');
        const errorBanner = document.getElementById('errorBanner');
        const submitBtn = document.getElementById('submitBtn');

        // Pull email + token straight from the reset link's query string
        // e.g. /reset-password?token=xxxx&email=user%40example.com
        const params = new URLSearchParams(window.location.search);
        const email = params.get('email');
        const token = params.get('token');

        function clearErrors() {
            newPassword.classList.remove('error');
            confirmPassword.classList.remove('error');
            newPasswordError.textContent = '';
            confirmPasswordError.textContent = '';
            errorBanner.style.display = 'none';
        }

        function showBannerError(message) {
            errorBanner.textContent = message;
            errorBanner.style.display = 'block';
        }

        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            successBanner.style.display = 'none';
            clearErrors();

            let isValid = true;
            const pwdValue = newPassword.value;
            const confirmValue = confirmPassword.value;

            // Make sure the link actually has email + token
            if (!email || !token) {
                showBannerError('This reset link is invalid or missing required data. Please request a new one.');
                return;
            }

            // New password required + minimum length
            if (pwdValue.length === 0) {
                newPasswordError.textContent = 'Password is required.';
                newPassword.classList.add('error');
                isValid = false;
            } else if (pwdValue.length < 8) {
                newPasswordError.textContent = 'Password must be at least 8 characters.';
                newPassword.classList.add('error');
                isValid = false;
            }

            // Confirm password required + must match
            if (confirmValue.length === 0) {
                confirmPasswordError.textContent = 'Please confirm your password.';
                confirmPassword.classList.add('error');
                isValid = false;
            } else if (pwdValue !== confirmValue) {
                confirmPasswordError.textContent = 'Passwords do not match.';
                confirmPassword.classList.add('error');
                isValid = false;
            }

            if (!isValid) return;

            submitBtn.disabled = true;
            submitBtn.textContent = 'Updating...';

            try {
                const response = await fetch('/api/update-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        // If you're using Laravel Sanctum / CSRF cookies, also send the
                        // X-XSRF-TOKEN header here, read from the XSRF-TOKEN cookie.
                    },
                    body: JSON.stringify({
                        email: email,
                        token: token,
                        new_password: pwdValue,
                        new_password_confirmation: confirmValue
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    successBanner.style.display = 'block';
                    form.reset();
                    submitBtn.textContent = 'Update Password';
                    submitBtn.disabled = true; // link is now used up
                    // Optionally redirect to login after a short delay:
                    // setTimeout(() => window.location.href = '/login', 2000);
                } else {
                    // Laravel validation errors come back as { message, errors: { field: [msgs] } }
                    if (data.errors) {
                        if (data.errors.token) {
                            showBannerError(data.errors.token[0]);
                        } else if (data.errors.email) {
                            showBannerError(data.errors.email[0]);
                        } else if (data.errors.new_password) {
                            newPasswordError.textContent = data.errors.new_password[0];
                            newPassword.classList.add('error');
                        } else {
                            showBannerError(data.message || 'Something went wrong. Please try again.');
                        }
                    } else {
                        showBannerError(data.message || 'Something went wrong. Please try again.');
                    }
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Update Password';
                }
            } catch (err) {
                showBannerError('Network error. Please check your connection and try again.');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Update Password';
            }
        });

        // Live-clear individual field errors on typing
        newPassword.addEventListener('input', function () {
            newPasswordError.textContent = '';
            newPassword.classList.remove('error');
        });
        confirmPassword.addEventListener('input', function () {
            confirmPasswordError.textContent = '';
            confirmPassword.classList.remove('error');
        });
    </script>

</body>
</html>