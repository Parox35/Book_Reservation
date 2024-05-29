//Block access to dates after those chosen for the start of the reservation
function updateMin(){
    var date = document.getElementById("StartReservation").value;
    document.getElementById("EndReservation").min = date;
}

