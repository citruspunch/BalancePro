document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById("errorModal");
    var span = modal ? modal.getElementsByClassName("close")[0] : null;

    if (modal) {
        modal.style.display = "block";
    }

    if (span) {
        span.onclick = function() {
            modal.style.display = "none";
        }
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
});