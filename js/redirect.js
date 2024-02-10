function redirectDash() {
    var loggedInUser = sessionStorage.getItem('loggedInUser');
    
    if (loggedInUser === 'mary@staff.makeitall.com') {
        window.location.href = 'a_dash.html';
    } else if (loggedInUser === 'tom@staff.makeitall.com') {
        window.location.href = 's_dash.html';
    } else {
        alert('ERROR: User has not logged into the system')
    }
}