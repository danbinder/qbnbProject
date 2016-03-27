//Global variable for error checking purposes

var errors = [1,1,1,1,1,1];

function checkFilled(field){
	var text = field.value;
	if ((text != "")){ //individual field meets requirements
		
		sendSpace(field);
		
		if ((errors[0] || errors[1] || errors[2] || errors[3] || errors[4] || errors[5]) == 0){ //valid in every field
		//bar out submit btn
			document.getElementById("createAccBtn").disabled = false;

		}
	} else {
		document.getElementById("createAccBtn").disabled = true;
		error(field);
	}
}

function error(field){
console.log(field.id);
	if (field.id == "email"){
		errors[0] = 1;
		document.getElementById("debug").innerHTML = "emailREQ";
	} else if (field.id == "password"){
		errors[1] = 1;
		document.getElementById("debug").innerHTML = "pw req";
	} else if (field.id == "first_name"){
		errors[2] = 1;
		document.getElementById("debug").innerHTML = "fname req";
	} else if (field.id == "last_name"){
		errors[3] = 1;
		document.getElementById("debug").innerHTML = "lname req";
	} else if (field.id == "phone_number"){
		errors[4] = 1;
		document.getElementById("debug").innerHTML = "phone # 10 digits pls";
	} else if (field.id == "year"){
		errors[5] = 1;
		document.getElementById("debug").innerHTML = "year req";
	}
}

function sendSpace(field){
console.log(field.id);
	if (field.id == "email"){
		errors[0] = 0;
	} else if (field.id == "password"){
		errors[1] = 0;
	} else if (field.id == "first_name"){
		errors[2] = 0;
	} else if (field.id == "last_name"){
		errors[3] = 0;
	} else if (field.id == "phone_number"){
		errors[4] = 0;
	} else if (field.id == "year"){
		errors[5] = 0;
	}
}

function checkNumber(field){
	var text = field.value;
	if (isNumber(text) &&  (text.length == 10)){ //individual field meets requirements
		if ((text != "")){ //individual field meets requirements
		
			sendSpace(field);
		
			if ((errors[0] || errors[1] || errors[2] || errors[3] || errors[4] || errors[5]) == 0){ //valid in every field
			//bar out submit btn
			document.getElementById("createAccBtn").disabled = false;

			}	
			} else {
		document.getElementById("createAccBtn").disabled = true;
		error(field);
		}
		
		} else {
			
			document.getElementById("createAccBtn").disabled = true;
			error(field);
		}
	}

function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}