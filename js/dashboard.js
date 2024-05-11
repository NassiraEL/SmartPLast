let logoutBotton = document.querySelector("#logout");
let spanAdmin = document.getElementById("admin");
let spanCollector = document.getElementById("collector");
let spanCommand = document.getElementById("command");
let spanPartner = document.getElementById("partner");


logoutBotton.addEventListener("click", function(){
    let xml = new XMLHttpRequest();
    xml.open("GET", "../destroy.php", "true");
    xml.setRequestHeader("Content-Type", "application/json")
    xml.onload = function(){
        if(xml.readyState === XMLHttpRequest.DONE){
            if(xml.status === 200){
                if(this.responseText == "true"){
                    window.location.href = "../index.html";
                }
            }
        }     
        
    }
    xml.send();
    
})

fetch("dashboard.php",  {
    headers: {
        "Content-Type": "application/json"
    }
})
.then(responce => responce.json())
.then(data=> {
    console.log(data);
    spanAdmin.innerHTML = data['TOTAL_ADMIN'];
    spanCollector.innerHTML = data['TOTAL_COLLECTOR'];
    spanCommand.innerHTML = data['TOTAL_COMMAND'];
    spanPartner.innerHTML = data['TOTAL_PARTNER'];
    
});

  