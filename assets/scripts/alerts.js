messages = document.querySelectorAll('.alert')

for (message of messages) {
    message.addEventListener('click', function (e) {
        e.target.parentElement.removeChild(e.target)
    })
}