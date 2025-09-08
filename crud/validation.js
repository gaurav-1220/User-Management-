document.addEventListener('DOMContentLoaded', function() {
    
    const signupForm = document.getElementById('signup-form');
    const nameInput = document.getElementById('name');
    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    
    const errorContainer = document.getElementById('error-message-container');
    const errorText = document.getElementById('error-text');

    signupForm.addEventListener('submit', function(event) {
        
        event.preventDefault();

        const name = nameInput.value.trim();
        const username = usernameInput.value.trim();
        const email = emailInput.value.trim();
        const password = passwordInput.value.trim();
        const confirmPassword = confirmPasswordInput.value.trim();


        if (name === '') {
            showError("Name cannot be empty.");
            return;
        }

        const nameRegex = /^[A-Za-z\s]+$/;
        if (!nameRegex.test(name)) {
            showError("Name can only contain letters and spaces.");
            return;
        }

        if (username === '') {
            showError("Username cannot be empty.");
            return;
        }

        const usernameRegex = /^[A-Za-z0-9_]+$/;
        if (!usernameRegex.test(username)) {
            showError("Username can only contain letters, numbers, and underscores (_).");
            return;
        }

        if (email === '') {
            showError("Email cannot be empty.");
            return;
        }
        
        const emailRegex = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
        if (!emailRegex.test(email)) {
            showError("Please enter a valid email address.");
            return;
        }

        if (password === '') {
            showError("Password cannot be empty.");
            return;
        }

        if (password !== confirmPassword) {
            showError("Passwords do not match.");
            return;
        }

        if (password.length < 8) {
            showError("Password must be at least 8 characters long.");
            return;
        }
        
        const specialCharRegex = /[^A-Za-z0-9]/;
        if (!specialCharRegex.test(password)) {
            showError("Password must contain at least one special character (e.g., !, @, #, $)");
            return;
        }

        hideError();
        signupForm.submit();
    });

    function showError(message) {
        errorText.textContent = message;
        errorContainer.style.display = 'block';
    }
    
    function hideError() {
        errorContainer.style.display = 'none';
    }
});