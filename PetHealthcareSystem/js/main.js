function validation() {
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirm-password").value;
    
    // Regular expression to check if password meets the requirements
    const passwordPattern = /^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/;

    if (!passwordPattern.test(password)) {
        alert("Password must be at least 8 characters long, contain at least one uppercase letter, and include at least one number.");
        return false; // Prevent form submission
    }

    if (password !== confirmPassword) {
        alert("Passwords do not match.");
        return false; // Prevent form submission
    }

    return true; // Allow form submission
}

function validateForm() {
    const name = document.getElementById('name').value.trim();
    const type = document.getElementById('type').value;
    const breed = document.getElementById('breed').value.trim();
    const age = document.getElementById('age').value.trim();
    const preferences = document.getElementById('preferences').value.trim();
    const image = document.getElementById('image').files.length;

    if (!name || !type || !breed || !age || !preferences || image === 0) {
        alert('Please fill in all fields and select an image.');
        return false;
    }
    return true;
}

// Preview the selected image
function previewImage() {
    const file = document.getElementById('image').files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('image-preview').src = e.target.result;
            document.getElementById('image-preview').style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // Get the current page URL
    const currentUrl = window.location.href;

    // Log the current URL to help with debugging
    console.log("Current URL:", currentUrl);

    // Select all links in the navbar
    const navLinks = document.querySelectorAll('.navbar a');

    // Loop through each link
    navLinks.forEach(link => {
        // Log the full link URL for comparison
        console.log("Link URL:", link.href);

        // If the link's full URL matches the current URL, set it as active
        if (currentUrl === link.href) {
            link.classList.add('active');
        }
    });
});
