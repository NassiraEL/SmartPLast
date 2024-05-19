let section1 = document.querySelectorAll(".section1");
let section2 = document.querySelector(".section2");
let iconCommand = document.querySelector(".iconCommand");
let valide1 = document.querySelector(".validate1");
let valide2 = document.querySelector(".validate2");
let valide3 = document.querySelector(".validate3");
let state1 = document.querySelector(".state1");
let state2 = document.querySelector(".state2");
let state3 = document.querySelector(".state3");
let newCammand = document.querySelector(".section2 p");
let logout = document.querySelector(".logout");
let section3 = document.querySelector(".section3");
let iconDonnation = document.querySelector(".iconDonnation");
let partPartner = document.querySelector(".partPartner");

let xml = new XMLHttpRequest();
xml.open("GET", "profilPartner.php", true);
xml.setRequestHeader("Content-type", "application/json");
xml.send();
xml.onload = function (){
    if(xml.readyState === XMLHttpRequest.DONE){
        if(xml.status === 200){
            let data = JSON.parse(xml.responseText);
            console.log(data);
            switch(data[0]) {
                case 'ATTEND':
                    section2.style.display = "flex";
                    valide1.style.visibility = "visible";
                    state1.style.visibility = "visible";
                    if(data[1] != null){
                        section3.innerHTML = data[1];
                    }
                    break;
                case 'INPROCESS':
                    section2.style.display = "flex";
                    valide1.style.visibility = "visible";
                    valide2.style.visibility = "visible";
                    state2.style.visibility = "visible";
                    if(data[1] != null){
                        section3.innerHTML = data[1];
                    }
                    break;
                case 'DONE':
                    if(data[2] == "yes"){
                        section2.style.display = "flex";
                        valide1.style.visibility = "visible";
                        valide2.style.visibility = "visible";
                        valide3.style.visibility = "visible";
                        state3.style.visibility = "visible";
                        newCammand.style.visibility = "visible";
                    }else{
                        section1.forEach(e=>{
                            e.style.display = "flex";
                        })
                    }
                    if(data[1] != null){
                        section3.innerHTML = data[1];
                    }
                    break;
                default:
                    section1.forEach(e=>{
                        e.style.display = "flex";
                    })
            }  
            
        }
      }
    
};

newCammand.addEventListener("click", function(){
    section2.style.display = "none";
    section1.forEach(e=>{
        e.style.display = "flex";
    })
    
})

function addCommand(type){
    let xml = new XMLHttpRequest();
        xml.open("GET", "addCommand.php?typeCommand="+type, "true");
        xml.setRequestHeader("Content-Type", "application/json")
        xml.onload = function(){
            if(xml.readyState === XMLHttpRequest.DONE){
                if(xml.status === 200){
                    if(this.responseText == "true"){
                        window.location.href = "profilPartner.html";
                    }
                }
            }
            
            
        }
        xml.send();
}

iconCommand.addEventListener("click", function(){
    partPartner.style.opacity = "0.4";
    partPartner.style.backgroundColor = "#D6D6D6";

    confirmation("هل انت متاكد من طلب التفريغ ؟ ")
    .then(data =>{
        if(data == true){
            partPartner.style.opacity = "1";
            partPartner.style.backgroundColor = "transparent";
            this.style.zIndex = "-2"; 
            document.querySelector(".trush").classList.add("trushAnimation");
            setTimeout(function(){
            addCommand(0);
            }, 2500);
        }else{
            partPartner.style.opacity = "1";
            partPartner.style.backgroundColor = "transparent";
        }
    })
    
})

iconDonnation.addEventListener("click", function(){
    
    partPartner.style.opacity = "0.4";
    partPartner.style.backgroundColor = "#D6D6D6";

    confirmation("هل انت متاكد من التبرع بالبلاستيك ؟ ")
    .then(data =>{
        if(data == true){
            notification("شكراً لتبرعكم ")
            .then(data =>{
                if(data == true){
                    partPartner.style.opacity = "1";
                    partPartner.style.backgroundColor = "transparent";
                    addCommand(1);
                }
            })
            
        }else{
            partPartner.style.opacity = "1";
            partPartner.style.backgroundColor = "transparent";
        }
    })
    
})



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


//confirmation windows
function confirmation(msg){
    //cree window
    let window_conf = document.createElement("div");

    //cree titel
    let titel = document.createElement("h1");
    let titelText = document.createTextNode(msg);
    titel.append(titelText);

    //cree buttons YES and NO
    let btnYes = document.createElement("button");
    let btnYesContent = document.createTextNode("نعم");
    btnYes.append(btnYesContent);
    btnYes.id = "btnYes";

    let btnNo = document.createElement("button");
    let btnNoContent = document.createTextNode("لا");
    btnNo.append(btnNoContent);
    btnNo.id = "btnNo";

    //div contient all buttons 
    let Buttons_Window_conf = document.createElement("div");
    Buttons_Window_conf.append(btnYes);
    Buttons_Window_conf.append(btnNo);


    //append element in window
    window_conf.append(titel);
    window_conf.append(Buttons_Window_conf);

    window_conf.classList.add("window_conf");

    document.body.append(window_conf);

    return new Promise((clickButton)=>{
        btnYes.onclick = () =>{
            clickButton(true);
           document.body.removeChild(window_conf);
        }

        btnNo.onclick = () =>{
            clickButton(false);
           document.body.removeChild(window_conf);
        }
    })

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