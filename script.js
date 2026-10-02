document.addEventListener("DOMContentLoaded", function() {
    const registrationForm = document.getElementById("registrationForm");

    registrationForm.addEventListener("submit", function(event) {
        event.preventDefault();

        const name = document.getElementById("regFullName").value.trim();
        const email = document.getElementById("regEmail").value.trim();
        const password = document.getElementById("regPassword").value;
        const error = document.getElementById("registrationError");

        if (!name || !email || !password) {
            error.textContent = "Please fill in all fields!";
            return;
        }

        alert("Registration Successful! Welcome, " + name + "!");

        document.getElementById("registrationPage").style.display = "none";
        document.getElementById("storefront").style.display = "block";
    });
});


function buyNow(product) {
    alert("You selected: " + product);
}

function greetUser() {
    let name = document.getElementById("name").value.trim();
    let age = Number(document.getElementById("age").value);
    let result = document.getElementById("result");

    if (name === "" || age <= 0 || !Number.isInteger(age)) {
        result.textContent = "Please enter a valid name and age.";
    } else if (age < 18) {
        result.textContent = "Hello " + name + "! You are a minor.";
    } else {
        result.textContent = "Hello " + name + "! You are an adult.";
    }
}


function submitForm(event) {
    event.preventDefault();

    const name = document.getElementById("nameInput").value.trim();
    const email = document.getElementById("emailInput").value.trim();
    const rating = document.getElementById("ratingInput").value;
    const suggestions = document.getElementById("suggestionsInput").value.trim();

    if (!name || !email || !rating || !suggestions) {
        document.getElementById("errorMessage").textContent =
            "Please fill in all fields!";
        return;
    }

    document.getElementById("outputName").textContent = name;
    document.getElementById("outputEmail").textContent = email;
    document.getElementById("outputRating").textContent = rating;
    document.getElementById("outputSuggestions").textContent = suggestions;

    document.getElementById("feedbackOutput").style.display = "block";
    document.getElementById("errorMessage").textContent = "";

    document.getElementById("feedbackForm").reset();
}