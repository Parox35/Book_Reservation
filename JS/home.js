function filter(){
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