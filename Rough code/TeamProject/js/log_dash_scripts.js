function validateLogin() {
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;

    var validCreds = {
        'tom@staff.makeitall.com': 'Pw112233*',
        'mary@staff.makeitall.com': 'Pw332211*' 
    };

    if (validCreds[email] && validCreds[email] === password) {
        sessionStorage.setItem('loggedInUser', email);

        if (email === 'mary@staff.makeitall.com'){
            window.location.href='a_dash.html';
        } else {
            window.location.href='s_dash.html';
        }
        return false;
    } else {
        alert('Invalid email or password. Please try again.');
        return false;
    }   
}



/* Future functionality: emails will be verified through the database, 
this will follow similar format to the prototype model*/
    