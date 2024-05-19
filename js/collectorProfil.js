let logout = document.querySelector(".logout");
let allCommand = document.querySelector(".allCommand");
let partCollector = document.querySelector(".partCollector");

logout.addEventListener("click", function(){
    let xml = new XMLHttpRequest();
    xml.open("GET", "destroy.php", "true");
    xml.setRequestHeader("Content-Type", "application/json")
    xml.onload = function(){
        if(xml.readyState === XMLHttpRequest.DONE){
            if(xml.status === 200){
                if(this.responseText == "true"){
                    window.location.href = "index.html";
                }
            }
        }     
        
    }
    xml.send();
    
})

let xml = new XMLHttpRequest();
xml.open("GET", "profilCollector.php", true);
xml.setRequestHeader("Content-Type", "application/json");
xml.send();
xml.onload = function(){
    console.log(xml.responseText);
    allCommand.innerHTML += xml.responseText;
}

function done(commandID, collectorID){
    let xml = new XMLHttpRequest();
    xml.open("POST", "doneCommand.php", true);
    xml.setRequestHeader("Content-Type", "application/json");
    let obj ={CM_id:commandID, CL_id:collectorID};
    let data = JSON.stringify(obj);
    xml.send(data);
    xml.onload = function(){
        if(xml.readyState === XMLHttpRequest.DONE){
            if(xml.status === 200){
                if(xml.responseText == "true"){
                    console.log(xml.responseText);
                    document.getElementById(commandID).querySelector(".state"). innerHTML = 'تم  التسليم';
                    document.getElementById(commandID).querySelector(".state").style.color = "#4CCD99";
        
                    setTimeout(function(){
                        document.getElementById(commandID).style.display = "none";
                    }, 2000);
                    
                }
            }
        }   
        
    }

}


function process(commandID, collectorID){
    let xml = new XMLHttpRequest();
    xml.open("POST", "processCommand.php", true);
    xml.setRequestHeader("Content-Type", "application/json");
    let obj ={CM_id:commandID, CL_id:collectorID};
    let data = JSON.stringify(obj);
    xml.send(data);
    xml.onload = function(){
        if(xml.readyState === XMLHttpRequest.DONE){
            if(xml.status === 200){
                if(xml.responseText == "true"){
                    console.log(xml.responseText);
                    document.getElementById(commandID).querySelector(".state"). innerHTML = 'قيد التجميع';
                    document.getElementById(commandID).querySelector(".state").style.color = "orange";
        
                    document.getElementById(commandID).querySelector(".encour").disabled = true;
                    document.getElementById(commandID).querySelector(".encour").style.background = "#4788f469";
                }
            }}
        
        
        
    }


}


function  locationUser(lat, lng) {
    if(lat == null || lng == null){
        partCollector.style.opacity = "0.4";
        partCollector.style.backgroundColor = "#D6D6D6";

        notification("لا يوجد موقع، يرجى التواصل مع الشريك ")
        .then(data =>{
            if(data == true){
                partCollector.style.opacity = "1";
                partCollector.style.backgroundColor = "transparent";
            }
        })
    }else{
        window.location.href = `https://www.google.com/maps/@${lat},${lng},20z?authuser=0&entry=ttu`;
    }
}


// alert function
function notification(msg){
    //cree window
    let window_alert = document.createElement("div");

    //cree titel
    let titel = document.createElement("h1");
    let titelText = document.createTextNode(msg);
    titel.append(titelText);

    //cree buttonok
    let btnOK = document.createElement("button");
    let btnOKContent = document.createTextNode("حسنًا");
    btnOK.append(btnOKContent);
    btnOK.id = "btnYes";


    //div contient all buttons 
    let Buttons_Window_alert = document.createElement("div");
    Buttons_Window_alert.append(btnOK);


    //append element in window
    window_alert.append(titel);
    window_alert.append(Buttons_Window_alert);

    window_alert.classList.add("window_alert");

    document.body.append(window_alert);

    return new Promise((clickButton)=>{
        btnOK.onclick = () =>{
            clickButton(true);
           document.body.removeChild(window_alert);
        }
    })

}