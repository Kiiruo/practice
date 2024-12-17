function closeModal() {
    document.getElementById('requestModal').style.display = "none";
}

document.getElementById('tour-request-form').onsubmit = function(event) {
    event.preventDefault();
    const formData = new FormData(this);

    fetch('submit_request.php', {
        method: 'POST',
        body: formData
    }).then(response => {
        return response.text();
    }).then(data => {
        alert('Запрос отправлен');
        closeModal();
    }).catch(error => {
        console.error('Ошибка:', error);
    });
};