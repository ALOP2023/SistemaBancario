document.getElementById('card-info').style.display = 'none';


function claveDinamica() {
    $.ajax({  
        url: 'http://localhost/Sistema%20Bancario/backend/app/Http/Controllers/claveDinamica.php', 
        method: 'GET',  
        dataType: 'json', 
        success: function(data) {
            if (data.status === 1) {
                document.getElementById('claveDinamica').innerHTML = data.data;
            } else {
                console.error('Error:', data.data);
            }
        },   
        error: function(error) {  
            console.error('Error:', error);  
        }  
    });  
}

function getDataUsers(cuenta) {
    $.ajax({  
        url: 'http://localhost/Sistema%20Bancario/backend/app/Http/Controllers/getDataUser.php', 
        method: 'POST',  
        dataType: 'json', 
        data: {
            nro_cuenta: cuenta
        },
        success: function(response) {
            if (response.status === 1) {
                document.getElementById('usuario_cuenta').innerHTML = response.data.nombre;
                document.getElementById('nro_cuenta').innerHTML = response.data.nro_cuenta;
                document.getElementById('saldo').innerHTML = response.data.saldo;
                // console.log(document.getElementById('saldo').innerHTML);
            } else {
                alert(response.data);
                return;
            }
        },   
        error: function(error) {  
            alert(error);  
            return;
        }  
    });  
}

function identificarTipoRetiro(tipoRetiro) {
    const claveInput = document.getElementById('claveRetiro');
    const clave4d = document.getElementById('clave4digitos');
    const claveDinamicaElement = document.getElementById('show-claveDinamica');
    document.getElementById('tipo-retiro').value = tipoRetiro;
    
    switch(tipoRetiro) {
        case 'nequi':
            console.log('nequi');
            claveDinamicaElement.style.display = 'block'; 
            break;
            
        case 'ahorro_mano':
            console.log('ahorro_mano');
            claveDinamicaElement.style.display = 'none'; 
            claveInput.placeholder = "Ingrese la clave de 4 dígitos";
            clave4d.textContent = "Digite su clave de 4 dígitos";
            break;
            
        case 'cuenta_ahorros':
            console.log('cuenta_ahorros');
            claveDinamicaElement.style.display = 'none'; 
            claveInput.placeholder = "Ingrese la clave de 4 dígitos";
            clave4d.textContent = "Digite su clave de 4 dígitos";
            break;
    }
}

function verifyAccount() {
    const input = document.getElementById('cuenta').value;
    const regex = /^[0-9]*$/;
    const t_retiro = document.getElementById('tipo-retiro').value;
    if (t_retiro == 'nequi') {
        if (input.length != 10) {
            alert("N° de cuenta debe ser de 10 dígitos");
            return;            
        }
        if (regex.test(input) == false) {
            alert("N° de cuenta debe tener solo números");   
            return;         
        } 
    }
    if (t_retiro == 'ahorro_mano') {
        if (input.length != 11) {
            alert("N° de cuenta debe ser de 11 dígitos");
            return;            
        }
        if (regex.test(input) == false) {
            alert("N° de cuenta debe tener solo números");   
            return;         
        }
        if (input.charAt(0) != '1'&& input.charAt(0) != '0'){
            alert("N° de cuenta Inválido. Debe empezar con 1 o 0");   
            return;         
        } 
        if (input.charAt(1) != '3'){
            alert("N° de cuenta Inválido");   
            return;         
        }
    }

    if (t_retiro == 'cuenta_ahorros') {
        if (input.length != 11) {
            alert("N° de cuenta debe ser de 11 dígitos");
            return;            
        }
        if (regex.test(input) == false) {
            alert("N° de cuenta debe tener solo números");   
            return;         
        }
    }
    
    getDataUsers(input);
    document.getElementById('card-cuenta').style.display = 'none';
    document.getElementById('card-info').style.display = 'block';
}

function retiro() {
    const btn1 = document.getElementById('btnradio1');
    const btn2 = document.getElementById('btnradio2');
    const btn3 = document.getElementById('btnradio3');
    const btn4 = document.getElementById('btnradio4');
    var btnValue = document.getElementById('montoRetiro').value; 
    if (btnValue == "") {
        if (btn1.checked) {
            btnValue = 10000;
        }
        if (btn2.checked) {
            btnValue = 20000;
        }
        if (btn3.checked) {
            btnValue = 50000;
        }
        if (btn4.checked) {
            btnValue = 100000;
        }
    }

    $.ajax({  
        url: 'http://localhost/Sistema%20Bancario/backend/app/Http/Controllers/retiro.php', 
        method: 'POST',  
        dataType: 'json', 
        data: {
            valorRetiro: btnValue,
            nro_cuenta: document.getElementById('cuenta').value,
            clave: document.getElementById('claveRetiro').value
        },
        success: function(response) {
            console.log(response);
            if (response.status === 1) {
                getDataUsers(document.getElementById('cuenta').value);
                var titular = document.getElementById('usuario_cuenta').innerHTML;
                var cuenta = document.getElementById('nro_cuenta').innerHTML; 
                var saldo = document.getElementById('saldo').innerHTML; 
                console.log(saldo);

                document.getElementById('reciboTitular').innerHTML = titular;
                document.getElementById('reciboNroCuenta').innerHTML = cuenta;
                document.getElementById('reciboSaldoDisponible').innerHTML = response.data.saldoTotal ;
                document.getElementById('reciboValorRetirado').innerHTML = response.data.valor_retirado;
                console.log(response.data.saldoTotal);

                // LLENAMOS MODAL DE RECIBO
                const billetes = response.data.billetes_entregados;
                document.getElementById('reciboBilletes100').textContent = billetes['100000'];
                document.getElementById('reciboBilletes50').textContent = billetes['50000'];
                document.getElementById('reciboBilletes20').textContent = billetes['20000'];
                document.getElementById('reciboBilletes10').textContent = billetes['10000'];

                $('#modalRecibo').modal('show');
            } else {
                alert(response.data);
                return;
            }
        },   
        error: function(error) {  
            alert(error);  
        }  
    });  
    
}

function Llenar(params) {
    
}
// Ejecutar inmediatamente y luego cada 6 segundos
setInterval(claveDinamica, 3*60*1000);
claveDinamica();


// verifyAccount()

// const collapseElementList = document.querySelectorAll('.collapse');
// const collapseList = [...collapseElementList].map(collapseEl => new bootstrap.Collapse(collapseEl));
