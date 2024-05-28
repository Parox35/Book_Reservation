function signFunction(){
    if(document.getElementById("div_register").classList.contains("collapse")){
        document.getElementById("div_register").classList.remove("collapse");
        document.getElementById("div_login").classList.add("collapse");
    }
}

function loginFunction(){
    if(document.getElementById("div_login").classList.contains("collapse")){
        document.getElementById("div_login").classList.remove("collapse");
        document.getElementById("div_register").classList.add("collapse");
    }
}