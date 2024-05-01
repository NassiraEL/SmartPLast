let logoutBotton = document.querySelector("#logout");
let tbody = document.getElementById("tbody");
let section_orders = document.querySelector(".orders"); 
let section_data_partner = document.querySelector(".data_partner"); 
let closeWindows = document.querySelectorAll(".closeWindow"); 
let btnAddCommand = document.getElementById("btnAddCommand");
let btnNewOrder = document.querySelector(".btnNewOrder");
let window_addCommand  = document.querySelector(".addCommand");
let selectPartner = window_addCommand.querySelector("#partner");
let selectCollector = window_addCommand.querySelector("#collector");
let divReponce = window_addCommand.querySelector(".reponce");
let allinputs = section_data_partner.querySelector(".all_data_Partner");
let typeStates = [['ATTEND', 'red', 'في الانتظار'], ['INPROCESS', '#F69F0C', 'قيد التجميع'], ['DONE', '#4CCD99', 'تم التسليم']]; 


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

// Search for all orders or specific ones.
function search_command(word='', type_table){
    let data_send = [word, type_table];

    fetch("search.php", {
        method: 'POST',
        headers: {
            "Content-type": "application/json"
        },
        body: JSON.stringify(data_send)
    })
    .then(rep => rep.json())
    .then(data => {
        console.log(data);

        if(data == null){
            tbody.innerHTML  = "<tr><td colspan='4'> لا يوجد نتائج</td></tr>";
        }else{
            tbody.innerHTML  = "";
            data.forEach(command => {

                let rowHTML = `<tr>
                <td>`;
                if(command.state == "DONE"){
                    rowHTML += ` <select name="select_collector" class="select_collector" id="${command.commandID}" disabled>`;
                }else{
                    rowHTML += ` <select name="select_collector" class="select_collector" id="${command.commandID}" onchange="set_Collecor_Command(${command.commandID})">`;
                }
                
                
                command.collector.forEach((collector, index) => {
                    if (index == 0 && collector.name == null) {
                        rowHTML += `<option value="null" selected>لا أحد</option>`;
                    } else if (index == 0 && collector.name != null) {
                        rowHTML += `<option value="${collector.id}" selected> ${collector.name}</option>`;
                    } else {
                        rowHTML += `<option value="${collector.id}"> ${collector.name}</option>`;
                    }
                });
                rowHTML += `</select>
                                  </td>
                                   <td><i class='fa fa-map-marker-alt' onclick="locationUser(${command.location[1]}, ${command.location[0]})"></i></td>
                                   <td>`;
                if(command.state == "DONE"){
                    rowHTML += `<select name="select_state" class="select_state S${command.commandID}"  disabled>`;
                }else {
                    rowHTML += `<select name="select_state" class="select_state S${command.commandID}"   onchange="set_state_command(${command.commandID})">`;
                }              
                typeStates.forEach(state => {
                    if (state[0] == command.state) {
                        rowHTML += `<option value="${state[0]}" selected> ${state[2]}</option>`;
                    } else {
                        rowHTML += `<option value="${state[0]}"> ${state[2]}</option>`;   
                    }
                });
    
                rowHTML += `</select>
                                </td>
                                <td><i class="fa fa-user" onclick="get_data_partner(['${command.partner[0]}', '${command.partner[1]}', '${command.partner[2]}', '${command.partner[3]}', '${command.partner[4]}']);"></i></td>
                            </tr>`;
                tbody.innerHTML += rowHTML;
            });

            let select_all_state = document.querySelectorAll(".select_state");

            select_all_state.forEach(select_state =>{
                typeStates.forEach(state => {
                    if (state[0] == select_state.value) {
                        select_state.style.backgroundColor = state[1];
                    }
                });
            })
            
        }
        
    });    
}

search_command('', "command");


//location of the order
function  locationUser(lat, lng) {
    if(lat == null || lng == null){
        alert("لا يوجد موقع، يرجى التواصل مع الشريك لإضافته");
    }else{
        window.location.href = `https://www.google.com/maps/@${lat},${lng},20z?authuser=0&entry=ttu`;
    }
}

//change collector of the order
function set_Collecor_Command(commandID){
    let collecorID = document.getElementById(commandID).value;
    let setCollector_in_command = [commandID, collecorID, "collector"];
    console.log(setCollector_in_command);
    fetch("selectChange.php", {
        method : "POST",
        headers :{
            "Content-type" : "application/json"
        },
        body : JSON.stringify(setCollector_in_command)
    })
    .then(rep => rep.json())
    .then(data => {
        console.log(data, collecorID);
        let select_state = document.querySelector(`.S${commandID}`);
        if(data == "ATTEND"){
            select_state.style.backgroundColor = "red";
            select_state.value = "ATTEND";
            set_state_command(commandID)
        }else{
            select_state.style.backgroundColor = "#F69F0C";
            select_state.value = "INPROCESS";
            set_state_command(commandID)

        }
    })
    
}


//change state of  the order
function set_state_command(IDcmnd){
    let IDcol = document.getElementById(IDcmnd).value;
    let state = document.querySelector(`.S${IDcmnd}`).value;
    console.log(state , IDcol);
    if(state == "INPROCESS" && IDcol == "null"){
        alert("المرجو اختيار المجمع أولا !");
        document.querySelector(`.S${IDcmnd}`).value = "ATTEND";
    }else if(state == "DONE" && IDcol == "null"){
        alert("المرجو اختيار المجمع أولا !");
        document.querySelector(`.S${IDcmnd}`).value = "ATTEND";
    } else{
        let setState_in_command = [IDcmnd, IDcol, "state", state];
        fetch("selectChange.php", {
            method : "POST",
            headers :{
                "Content-type" : "application/json"
            },
            body : JSON.stringify(setState_in_command)
        })
        .then(rep => rep.json())
        .then(data=> {
            console.log(data)
            if(data == "ATTEND"){
                document.querySelector(`.S${IDcmnd}`).value = "ATTEND";
                document.querySelector(`.S${IDcmnd}`).style.backgroundColor = "red";
                document.getElementById(IDcmnd).value = null;
                console.log(document.querySelector(`.S${IDcmnd}`).value, document.getElementById(IDcmnd).value )
            }
            if(data == "DONE"){
                document.querySelector(`.S${IDcmnd}`).style.backgroundColor = "#4CCD99";
                document.querySelector(`.S${IDcmnd}`).value = "DONE";
                document.querySelector(`.S${IDcmnd}`).disabled = true;
                document.getElementById(IDcmnd).disabled = true;
            }
        })
    }

    
}


//Get data of the partner who placed the order
function get_data_partner(data_partner){
    section_orders.style.opacity = "0.4";
    section_orders.style.backgroundColor = "#D6D6D6";
    section_data_partner.style.display = "block";
    console.log(data_partner);

    let inputs = allinputs.querySelectorAll("input[type='text']");
    inputs[0].value = data_partner[0];
    inputs[1].value = data_partner[1];
    inputs[2].value = `0${data_partner[2]}`;

    function callFunction_location(){
        locationUser(data_partner[3],  data_partner[4]);
    }
}

//icon close windows
closeWindows.forEach(closeWindow=>{
    closeWindow.addEventListener("click", ()=>{
        closeWindow.parentElement.style.display = "none";
        section_orders.style.opacity = "1";
        section_orders.style.backgroundColor = "transparent";
        
    })
})


//open window add new order
btnNewOrder.addEventListener("click", ()=>{
    section_orders.style.opacity = "0.4";
    section_orders.style.backgroundColor = "#D6D6D6";
    window_addCommand.style.display = "block";
    divReponce.innerHTML = "";
    fetch("read.php")
    .then(rep=> rep.json())
    .then(data =>{
        console.log(data);
        selectPartner.innerHTML = "";
        selectCollector.innerHTML = "";
        
        data.allPartner.forEach(partner=>{
            selectPartner.innerHTML += `<option value="${partner[0]}">${partner[1]}</option>`
        })

        data.allCollector.forEach(collector=>{
            selectCollector.innerHTML += `<option value="${collector[0]}">${collector[1]}</option>`
        })
    })
})


//add new order to database
btnAddCommand.addEventListener("click", ()=>{
    let idPartner = selectPartner.value;
    let idCollector = selectCollector.value;
    let data ={
        typeTable : "command",
        content : [idPartner, idCollector]
    }

    fetch("add.php", {
        headers : {
            "Content-type" : "application/json"
        },
        method : "POST",
        body : JSON.stringify(data)
    })
    .then(rep => rep.json())
    .then(data =>{
        console.log(data);
        
        if(data == true){
            divReponce.innerHTML = `<h3 class="succes">تمت اضافة الطلب بنجاح</h3>`;
        }else{
            divReponce.innerHTML = `<h3 class="err">لم يتم إضافة الطلب </h3>`;
        }
    })
})
