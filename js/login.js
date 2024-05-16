const form = document.querySelector("form");
let btn = document.getElementById("btn");
let rep = document.getElementById("reponce");

form.addEventListener("submit", e =>{
    e.preventDefault();
})

btn.addEventListener("click", function(){
    let email = form.elements.email.value;
    let password = form.elements.password.value;

    let xml = new XMLHttpRequest();
    xml.open("POST", "login.php", true);
    xml.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xml.send("email="+email +"&password="+password);
    xml.onload = function (){
        if(xml.readyState === XMLHttpRequest.DONE){
            if(xml.status === 200){
                if(xml.responseText == "partner"){
                    window.location.href = `profilPartner.html`;
                }else if(xml.responseText == "collector"){
                    window.location.href = `profilCollector.html`;
                }else if(xml.responseText == "admin"){
                    window.location.href = `admin/dashboard.html`;
                }else{
                    rep.innerHTML = `<input type='text' disabled value='${xml.responseText}' style='background:#d64040e2; color:#fff;'>`;
                    console.log(xml.responseText);
                }
            }
        }
    }
})


//login with google
function hundelCredentialResponse(response){
    console.log(response);
     // Post JWT token to server-side
    fetch("loginWithGoogle.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ request_type:'user_auth', credential: response.credential }),
    })
    .then(response => response.json())
    .then(data => {
        if(data == true){
            window.location.href = `profilPartner.html`;
        }
    })
    .catch(console.error);
}