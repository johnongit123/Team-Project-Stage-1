function toggleAnswer(answerId) {
    var answer = document.getElementById(answerId);
    if (answer.style.display === "block" || answer.style.display === "") {
        answer.style.display = "none";
    } else {
        answer.style.display = "block";
    }
}

document.getElementById("submit-button").addEventListener("click", function() {
    // Perform actions when the submit button is clicked
    var selectedCategory = document.getElementById("error-category").value;
    var errorDescription = document.getElementById("error-description").value;

    // You can handle the form data here (e.g., send it to a server, process it, etc.)
    console.log("Selected Category: " + selectedCategory);
    console.log("Error Description: " + errorDescription);
});




/* Future functionality: redirectdash will be integrated with the database
 to verify if a user is admin or not and that will be used as means of redirect 
 
Typed info should deliver to Manager Or possible a Maintenance role 
(possible construction of new tab, changes would be made on sidebar
and landing page to accomadate)

redirectdash() will be modified to check whether a user is manager(admin) or employee(staff)
from the database instead of the prototype emails
 */