const nameInput = document.getElementById('nameInput');
const emailInput = document.getElementById('emailInput');
const ratingInput = document.getElementById('ratingInput');
const suggestionsInput = document.getElementById('suggestionsInput');

const submitButton = document.getElementById('submitButton');
const errorMessage = document.getElementById('errorMessage');
const outputDiv = document.getElementById('feedbackOutput');

const outputName = document.getElementById('outputName');
const outputEmail = document.getElementById('outputEmail');
const outputRating = document.getElementById('outputRating');
const outputSuggestions = document.getElementById('outputSuggestions');


submitButton.addEventListener('click', function(event) {

    event.preventDefault();

    const name = nameInput.value.trim();
    const email = emailInput.value.trim();
    const rating = ratingInput.value;
    const suggestions = suggestionsInput.value.trim();

   
    if (!name || !email || !rating || !suggestions) {
        errorMessage.textContent = "Please fill in all fields.";
        return;
    }
    if (rating < 1 || rating > 5) {
        errorMessage.textContent = "Rating must be between 1 and 5.";
        return;
    }

    errorMessage.textContent = "";
  
    outputName.textContent = name;
    outputEmail.textContent = email;
    outputRating.textContent = rating;
    outputSuggestions.textContent = suggestions;


    outputDiv.style.display = "block";
});