let section1 = document.querySelector(".section1");
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
                        section1.style.display = "flex";
                    }
                    if(data[1] != null){
                        section3.innerHTML = data[1];
                    }
                    break;
                default:
                    section1.style.display = "flex";
            }  
            
        }
      }
    
};

newCammand.addEventListener("click", function(){
    section2.style.display = "none";
    section1.style.display = "flex";
})

function addCommand(){
    let xml = new XMLHttpRequest();
        xml.open("GET", "addCommand.php", "true");
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
    const confirmation = confirm("هل انت متاكد من طلب التفريغ ؟ ");
    if (confirmation) {
        this.style.zIndex = "-2"; 
        document.querySelector(".trush").classList.add("trushAnimation");
        setTimeout(function(){
        addCommand();
        }, 2500);
    }
    
})



logout.addEventListener("click", function(){
    let xml = new XMLHttpRequest();
    xml.open("GET", "destroy.php", "true");
    xml.setRequestHeader("Content-Type", "application/json")
    xml.onload = function(){
        if(xml.readyState === XMLHttpRequest.DONE){
            if(xml.status === 200){
                if(this.responseText == "true"){
                    window.location.href = "home.html";
                }
            }
        }     
        
    }
    xml.send();
    
})





