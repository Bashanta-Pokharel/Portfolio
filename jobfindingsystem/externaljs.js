// Simple client-side form validation using Regex

function validateForm(event) {
    // Stop form submission until validation passes
    event.preventDefault();

    let cname = document.querySelector('input[name="cname"]');
    let username = document.querySelector('input[name="username"]');
    let password = document.querySelector('input[name="password"]');
    let email = document.querySelector('input[name="email"]');
    let address = document.querySelector('input[name="address"]');
    let pan = document.querySelector('input[name="pan"]');
    let license = document.querySelector('input[name="license"]');
    let category = document.querySelector('select[name="category"]');

    // ✅ Simple Regex patterns
    const usernameRegex = /^[a-zA-Z0-9_]{4,15}$/; // letters, numbers, underscore only
    const emailRegex = /^[\w.-]+@[a-zA-Z\d.-]+\.[a-zA-Z]{2,}$/;
    const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/; // at least one letter, one number, 6+ chars

    // Reset previous error messages
    document.querySelectorAll(".error").forEach(e => e.innerText = "");

    let valid = true;

    if (cname && cname.value.trim() === "") {
        cname.nextElementSibling.innerText = "*Company name required";
        valid = false;
    }

    if (username && !usernameRegex.test(username.value)) {
        username.nextElementSibling.innerText = "*Invalid username (4–15 letters/numbers/_)";
        valid = false;
    }

    if (password && !passwordRegex.test(password.value)) {
        password.nextElementSibling.innerText = "*Password must have 6+ chars, at least one letter & number";
        valid = false;
    }

    if (email && !emailRegex.test(email.value)) {
        email.nextElementSibling.innerText = "*Invalid email address";
        valid = false;
    }

    if (address && address.value.trim() === "") {
        address.nextElementSibling.innerText = "*Address required";
        valid = false;
    }

    if (pan && pan.value.trim() === "") {
        pan.nextElementSibling.innerText = "*Company PAN required";
        valid = false;
    }

    if (license && license.value.trim() === "") {
        license.nextElementSibling.innerText = "*Company license required";
        valid = false;
    }

    if (category && category.value === "") {
        category.nextElementSibling.innerText = "*Please select a category";
        valid = false;
    }

    // ✅ If all validations passed, allow submission
    if (valid) {
        event.target.submit();
    }
}
