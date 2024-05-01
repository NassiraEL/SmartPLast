const form = document.querySelector("form");
let btn = document.getElementById("btn");
let rep = document.getElementById("reponce");
let section1 = document.getElementById("section1");
let section2 = document.getElementById("section2");


form.addEventListener("submit", e =>{
    e.preventDefault();
})



btn.addEventListener("click", function(){
    let nameUser = form.elements.name.value;
    let tel = form.elements.tel.value;
    let email = form.elements.email.value;
    let pass = form.elements.password.value;
    let passConf = form.elements.passwordConf.value;
    let latitude = form.elements.latitude.value;
    let longitude = form.elements.longitude.value;


    let xml = new XMLHttpRequest();
    xml.open("POST", "signUp.php", true);
    xml.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
    xml.send("name="+nameUser +"&tel="+tel +"&email="+email +"&password="+pass +"&passwordConf="+passConf +"&latitude="+latitude +"&longitude="+longitude);
    xml.onload = function (){
        if(xml.readyState === XMLHttpRequest.DONE){
            if(xml.status === 200){
                if(xml.responseText === "true"){
                    window.location.href = `login.html`;
                }else{
                    rep.innerHTML = `<input type='text' disabled value='${xml.responseText}' style='background:#d64040e2; color:#fff;'>`;
                    console.log(xml.responseText);
                }
                
            }
          }
        
    };
    

})

