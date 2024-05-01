let btnMenu = document.getElementById("menu");
let nav2 = document.querySelector(".nav2");
let btnClose = document.getElementById("close");
let liens = document.querySelectorAll(".nav2 a");

btnMenu.addEventListener("click", function(){
    nav2.style.display = "flex";
})

btnClose.addEventListener("click", function(){
    nav2.style.display = "none";
})

liens.forEach(lien=>{
    lien.addEventListener("click", function(){
        nav2.style.display = "none";
    })
})