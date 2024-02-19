document.addEventListener('DOMContentLoaded', function() {
    const createThreadButton = document.getElementById('create_thread_button');
    createThreadButton.addEventListener('click', function(event) {
        event.preventDefault();
        const threadTitle = document.getElementById('thread_title').value;
        const authorName = document.getElementById('author_name').value;
        const threadContent = document.getElementById('thread_content').value;
        if (threadTitle === '' || authorName === '' || threadContent === '') {
            alert('Please fill out all fields before creating thread the form.');
        } else {
            const xhr = new XMLHttpRequest();
            const url = 'Test.php';
            console.log(threadTitle,authorName,threadContent);

            xhr.open('POST', url, true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            const data = 'thread_title=' + encodeURIComponent(threadTitle) +
                         '&author_name=' + encodeURIComponent(authorName) +
                         '&thread_content=' + encodeURIComponent(threadContent);

            xhr.send(data);

            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                    } else {
                        alert('An error occurred while processing your request.');
                    }
                }
            };
        }
    });
});
    
