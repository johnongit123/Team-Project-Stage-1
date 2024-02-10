document.getElementById('logoutLink').addEventListener('click', function(e) {
        e.preventDefault();
        sessionStorage.removeItem('isLoggedIn');
        window.location.href = 'landing.html';
});