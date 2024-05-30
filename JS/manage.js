//Function moving to reservation view to administrator view
function adminView(){
    document.getElementById("adminTable").classList.remove("collapse");
    document.getElementById("reservationTable").classList.add("collapse");
    document.getElementById("btnAdmin").disabled = true;
    document.getElementById("btnReservation").disabled = false;
}

//Function moving to administrator view to reservation view
function reservationView(){
    document.getElementById("reservationTable").classList.remove("collapse");
    document.getElementById("adminTable").classList.add("collapse");
    document.getElementById("btnReservation").disabled = true;
    document.getElementById("btnAdmin").disabled = false;
}

//Get the id of the reservation
var deleteModal = document.getElementById('deleteReservationModal');
deleteModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget; // Button that triggered the modal
    var id = button.getAttribute('data-id'); // Extract info from data-id attributes
    var modalBodyInput = deleteModal.querySelector('#id'); // Find the hidden input field in the modal
    modalBodyInput.value = id; // Set the value of the hidden input field
});