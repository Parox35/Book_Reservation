function showFilter(){
    if(document.getElementById("carretFilter").classList.contains("fa-caret-right")){
        document.getElementById("carretFilter").classList.remove("fa-caret-right");
        document.getElementById("carretFilter").classList.add("fa-caret-down");
        document.getElementById("filterItems").classList.remove("collapse");
    }
    else if (document.getElementById("carretFilter").classList.contains("fa-caret-down")) {
        document.getElementById("carretFilter").classList.remove("fa-caret-down");
        document.getElementById("carretFilter").classList.add("fa-caret-right");
        document.getElementById("filterItems").classList.add("collapse");
    }
}

var deleteModal = document.getElementById('deleteModal');
deleteModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget; // Button that triggered the modal
    var bookId = button.getAttribute('data-id'); // Extract info from data-* attributes
    var modalBodyInput = deleteModal.querySelector('#bookId'); // Find the hidden input field in the modal
    modalBodyInput.value = bookId; // Set the value of the hidden input field
});
deleteModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget; // Button that triggered the modal
    var bookId = button.getAttribute('data-id'); // Extract info from data-* attributes
    var bookTitle = button.getAttribute('data-title'); // Extract the title
    var modalBodyInput = deleteModal.querySelector('#bookId'); // Find the hidden input field in the modal
    var modalTitleSpan = deleteModal.querySelector('#bookTitle'); // Find the span for the book title

    modalBodyInput.value = bookId; // Set the value of the hidden input field
    modalTitleSpan.textContent = bookTitle; // Set the text content of the span
});