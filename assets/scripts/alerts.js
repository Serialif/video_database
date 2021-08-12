textAreas = document.querySelectorAll('textarea')

for (let textArea of textAreas) {
    textArea.style.height = '5px'
    textArea.style.height = (textArea.scrollHeight + 2) + 'px'

    textArea.addEventListener('keyup', function(){
        textArea.style.height = '5px'
        textArea.style.height = (textArea.scrollHeight + 2) + 'px'
    })
}