document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.querySelector('.sidebar');
    const btn = document.getElementById('btn');
    const header = document.querySelector('.header')
    const body = document.querySelector('body');
    


    btn.addEventListener('click', function () {
        sidebar.classList.toggle('active');
        header.classList.toggle('active');
    });

    body.addEventListener('click', function(event) {
        if (window.innerWidth <= 768 && sidebar.classList.contains('active') && header.classList.contains('active')
        && !sidebar.contains(event.target)) {
            sidebar.classList.remove('active');
            header.classList.remove('active');
        }
    });
});





    
