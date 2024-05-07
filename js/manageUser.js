let urlParams = new URLSearchParams(window.location.search);
let typeUser = urlParams.get('user');
let titelPage = document.querySelector(".titelPage");
let content_btnAdd = document.querySelector(".content_btnAdd");
let inputSearch = document.querySelector(".search input");
let tbody = document.getElementById("tbody");
let closeWindows = document.querySelectorAll(".closeWindow");
let partUser = document.querySelector(".partUser");
let window_edit = document.querySelector(".edit_data_user"); 
let allinputs_window_edit = window_edit.querySelector(".all_data_user");
let btnEdit = document.getElementById("btn_edit_User");
let btn_open_window_add = document.querySelector(".btnNewUser"); 
let window_add = document.querySelector(".addUser"); 
let allinputs_window_add = window_add.querySelector(".new_User");
let repEdit = document.querySelector(".repEdit"); 
let repAdd = document.querySelector(".repAdd"); 
let btnAddUser = document.getElementById("btn_add_User");
let titel_of_windowAdd = document.querySelector(".titel_of_windowAdd"); 
let logoutBotton = document.querySelector("#logout");

if(typeUser == "admin"){
    titelPage.innerHTML = "ادارة المسؤولين";
    content_btnAdd.innerHTML = "اضافة مسؤول";
    titel_of_windowAdd.innerHTML = "اضافة مسؤول";
}else if(typeUser == "collector"){
    titelPage.innerHTML = "ادارة المجمعين";
    content_btnAdd.innerHTML = "اضافة مجمع";
    titel_of_windowAdd.innerHTML = "اضافة مجمع";
}else{
    titelPage.innerHTML = "ادارة الشركاء";
    content_btnAdd.innerHTML = "اضافة شريك";
    titel_of_windowAdd.innerHTML = "اضافة شريك";
}


//search data of the user 
function search_User(word){
    let data_send = [word, typeUser];
    
    fetch("search.php", {
        method: 'POST',
        headers: {
            "Content-type": "application/json"
        },
        body: JSON.stringify(data_send)
    })
    .then(rep => rep.json())
    .then(data =>{
        console.log(data);
        if(data == null){
            tbody.innerHTML  = "<tr><td colspan='7'> لا يوجد نتائج</td></tr>";
        }else{
            tbody.innerHTML = "";
            data.forEach(user => {
                tbody.innerHTML += `<tr id="${user.id}">
                                        <td>${user.name}</td>
                                        <td>${user.email}</td>
                                        <td>0${user.phone}</td>
                                        <td>
                                            <i class='fa fa-map-marker-alt' onclick="locationUser(${user.latitude}, ${user.longitude})"></i>
                                        </td>
                                        <td>
                                            <select name="state_user" class="state_user U${user.id}" id="${user.state}" onchange="change_state_user(${user.id})">
                                                <option value="ACTIVE">نشط</option>
                                                <option value="INACTIVE">ملغي</option>
                                            </select>
                                        </td>
                                        <td><i class='fa fa-edit' onclick="edit_user(['${user.id}', '${user.name}','${user.email}', '${user.phone}', '${user.latitude}', '${user.longitude}'])"></i></td>
                                        <td><i class='fa fa-trash' onclick="delete_user(${user.id})"></i></td>
                                    </tr>`;
                
            });

            let All_select_state_user = document.querySelectorAll("tbody select");
            All_select_state_user.forEach(select =>{
                if(select.id == "ACTIVE" ){
                    select.value = "ACTIVE";
                    select.style.backgroundColor = "#4CCD99";
                }else{
                    select.value = "INACTIVE";
                    select.style.backgroundColor = "red";
                }
            })
        }
        
           
    })
}

search_User("");

//location of the order
function  locationUser(lat, lng) {
    if(lat == null || lng == null){
        alert("لا يوجد موقع، يرجى التواصل مع الشريك لإضافته");
    }else{
        window.location.href = `https://www.google.com/maps/@${lat},${lng},20z?authuser=0&entry=ttu`;
    }
}


//change state of user
function change_state_user(idUser){
    let state = document.querySelector(`select.U${idUser}`).value;
    let data_send = [idUser, state, typeUser];
    fetch("selectChange.php", {
        method: 'POST',
        headers: {
            "Content-type": "application/json"
        },
        body: JSON.stringify(data_send)
    })
    .then(rep => rep.json())
    .then(data =>{
        console.log(data);
        if(data == true){
            if(state == "ACTIVE"){
                document.querySelector(`.U${idUser}`).style.backgroundColor = "#4CCD99";
            }else{
                document.querySelector(`.U${idUser}`).style.backgroundColor = "red";
            }
            
        }
    })
}


//delete a user
function delete_user(idUser){
    let userConfirmation = confirm("هل أنت متأكد؟");
    if(userConfirmation){
        let data_send = [idUser, typeUser];
        fetch("delete.php", {
            method: 'POST',
            headers: {
                "Content-type": "application/json"
            },
            body: JSON.stringify(data_send)
        })
        .then(rep => rep.json())
        .then(data =>{
            console.log(data);
            if(data == true){
                document.querySelector(`.U${idUser}`).parentElement.parentElement.style.display = "none";
            }
        })
        
    }
    
}

//open window of edit data of the user 
function edit_user(arr){
    console.log(arr);
    
    partUser.style.opacity = "0.4";
    partUser.style.backgroundColor = "#D6D6D6";
    window_edit.style.display = "block";
    repEdit.innerHTML = "";

    let inputs = allinputs_window_edit.querySelectorAll("input");
    inputs[0].value = arr[1];
    inputs[1].value = arr[2];
    inputs[2].value = `0${arr[3]}`;
    inputs[3].value = `https://www.google.com/maps/@${arr[4]},${arr[5]},20z?authuser=0&entry=ttu`;
    inputs[4].value = arr[0];
}

//icon close windows
closeWindows.forEach(closeWindow=>{
    closeWindow.addEventListener("click", ()=>{
        closeWindow.parentElement.style.display = "none";
        partUser.style.opacity = "1";
        partUser.style.backgroundColor = "transparent";
        
    })
})

//edit data of the user
btnEdit.addEventListener("click",()=>{
        let inputs = allinputs_window_edit.querySelectorAll("input");

        let newData = [inputs[4].value, inputs[0].value, inputs[1].value, inputs[2].value, inputs[3].value];

        let data_send = [newData, typeUser];
        console.log(data_send)
        fetch("edit.php", {
            method: 'POST',
            headers: {
                "Content-type": "application/json"
            },
            body: JSON.stringify(data_send)
        })
        .then(rep => rep.json())
        .then(data =>{
            console.log(data);
            if(data[0] == 'succes'){
                repEdit.innerHTML = `<h3 class="succes">تم التعديل بنجاح</h3>`;
                let td = document.getElementById(`${inputs[4].value}`).querySelectorAll("td");
                td[0].innerHTML =  inputs[0].value;
                td[1].innerHTML =  inputs[1].value;
                td[2].innerHTML =  inputs[2].value;
                td[3].innerHTML =  `<i class='fa fa-map-marker-alt' onclick="locationUser(${data[1]}, ${data[2]})"></i>`;
                td[5].innerHTML =`<td><i class='fa fa-edit' onclick="edit_user(['${newData[0]}', '${newData[1]}','${newData[2]}', '${newData[3]}', '${data[1]}', '${data[2]}'])"></i></td>`;
                console.log(td[5])
            }else{
                repEdit.innerHTML = `<h3 class="err">${data[0]}</h3>`;
            }
        })
        
    
    
})

//open window of add a user 
btn_open_window_add.addEventListener("click", ()=>{
    partUser.style.opacity = "0.4";
    partUser.style.backgroundColor = "#D6D6D6";
    window_add.style.display = "block";
    repAdd.innerHTML = "";
    let inputs = allinputs_window_add.querySelectorAll("input");
    inputs.forEach((inp, i) =>{
        if(i !=  5){
            inp.value = '';
        }   
    })
})

//add new user
btnAddUser.addEventListener("click", ()=>{
    let inputs = allinputs_window_add.querySelectorAll("input");
    let newUser = [];

    inputs.forEach((inp, i) =>{
        if(i !=  5){
            newUser.push(inp.value);
        }   
    })

    let data_send = [typeUser, newUser];
    console.log(data_send);
    fetch("add.php", {
        method: 'POST',
        headers: {
            "Content-type": "application/json"
        },
        body: JSON.stringify(data_send)
    })
    .then(rep => rep.json())
    .then(data =>{
        console.log(data);
        if(data == true){
            repAdd.innerHTML = `<h3 class="succes">تمت الاضافة بنجاح</h3>`;
        }else{
            repAdd.innerHTML = `<h3 class="err">${data}</h3>`;
        }

    })
})


//logout botton
logoutBotton.addEventListener("click", function(){
    let xml = new XMLHttpRequest();
    xml.open("GET", "../destroy.php", "true");
    xml.setRequestHeader("Content-Type", "application/json")
    xml.onload = function(){
        if(xml.readyState === XMLHttpRequest.DONE){
            if(xml.status === 200){
                if(this.responseText == "true"){
                    window.location.href = "../home.html";
                }
            }
        }     
        
    }
    xml.send();
    
})