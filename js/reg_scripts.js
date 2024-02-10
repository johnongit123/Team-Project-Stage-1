function validateRegister() {
    var fullname = document.getElementById('fullname').value;
    var email = document.getElementById('email').value;
    var password = document.getElementById('password').value;
    var confirmPassword = document.getElementById('confirmPassword').value;


    var names = fullname.split(' ');
    if (names.length !== 2) {
        alert('Please only enter your First and Last Name')
        return false;
    }

    if(!email.includes('@staff.makeitall.com')){
        alert('Please enter a valid staff email')
        return false;
    }

    if (password !== confirmPassword) {
        alert('Passwords do not match');
        return false;
    }
    
    var strongRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&<>])[A-Za-z\d@$!%*?&<>]{8,}$/;


    if (!strongRegex.test(password)) {
        alert('Password must be at least 8 characters long and contains one lowercase, one uppercase, one special character and one digit');
        return false;
    }

    return true;
}



/* Future functionality: will allow registered emails and passwords 
to be stored in the database to be used as a login, 
employee(staff) and manager(admin) roles should also be assigned (in the database)
to help with faq to staff/admin dash javascript code*/