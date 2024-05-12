document.addEventListener('DOMContentLoaded', function () {
    var toastElems = document.querySelectorAll('.toast');
    toastElems.forEach(function(toastElem) {
        var toast = new bootstrap.Toast(toastElem);
        toast.show();
    });
});

function showToast(message, type = 'success') {
    var toastContainer = document.querySelector('.toast-container');
    var toastHtml = `
        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
            <div class="toast-header">
                <strong class="me-auto">Notification - ${type}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        </div>`;
    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    var newToast = toastContainer.lastElementChild;
    var toast = new bootstrap.Toast(newToast);
    toast.show();
}