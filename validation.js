function fileUploadValidation(){
	removeErrors('fileError');
	
	var filePath = document.getElementById('meme').value;
	var fileName = filePath.split('\\').pop().split('/').pop();
	var error = true;
	var forbiddenChars = /[^a-zA-Z0-9\._]/;
	var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
	
	if(forbiddenChars.test(fileName)){
		printErrorMessage('fileError', 'The filename contains forbidden characters. Accepted characters are A-Z, a-z, 0-9 and _ . ');
		error = false;
	}
	
	if((!allowedExtensions.test(fileName)) || ((fileName.match(/\./g) || []).length != 1)){
		printErrorMessage('fileError', 'Bad extension: accepted extensions are jpg, jpeg, png, gif. ');
		error = false;
	}
	
	if(document.getElementById('meme').files[0].size > 1024*1500){
		printErrorMessage('fileError','The file size is too big. I should not exceed 1500KB. ');
		error = false;
	}
	
	return error;
}

function signUpValidation(){
	var error = true;
	error = usernameValidation();
	error = passwordValidation();
	error = repasswordValidation();
	return error;
}

function usernameValidation(){
	removeErrors('usernameError');
		
	var error = true;
	var username = document.getElementById('username').value;
	var forbiddenChars = /[^a-zA-Z0-9_]/;
	if(forbiddenChars.test(username)){
		printErrorMessage('usernameError','The username can only contain a-z, A-Z and _ . ');
		error = false;
	}
	
	return error;
}

function passwordValidation(){
	removeErrors('passwordError');
	
	var error = true;
	var password = document.getElementById('password').value;
	
	if(password.length < 8){
		printErrorMessage('passwordError','The password should be at least 8 characters long. ');
		error = false;
	}
	
	if(password.length > 70){
		printErrorMessage('passwordError','The password should not exceed 70 characters. ');
		error = false;
	}
	
	if( (password.search(/[a-z]/) < 0 || password.search(/[A-Z]/) < 0 || password.search(/[0-9]/) < 0)){
		printErrorMessage('passwordError','The password should contain at least one small letter, one big letter and one number. ');
		error = false;
	}
	
	return error;
}

function repasswordValidation(){
	removeErrors('repasswordError');
	
	var error = true;
	var password = document.getElementById('password').value;
	var repassword = document.getElementById('repassword').value;
	if(!(password === repassword)){
		printErrorMessage('repasswordError','The passwords are not the same. ');
		error = false;
	}
	
	return error;
}

function signInValidation(){
	removeErrors('signInError');
	
	var username = document.getElementById('username').value;
	var password = document.getElementById('password').value;
	var error = true; 
	var forbiddenChars = /[^a-zA-Z0-9_]/;
	if(forbiddenChars.test(username)){
		error = false;
	}
	if(password < 8 || password >= 70){
		error = false;
	}
	if( (password.search(/[a-z]/) < 0 || password.search(/[A-Z]/) < 0 && password.search(/[0-9]/) < 0)){
		error = false;
	}
	
	if(error != true){
		printErrorMessage('signInError','Wrong username or password.');
	}
	
	return error;
}

function removeErrors(errorID){
	while(document.getElementById(errorID).firstChild){
		document.getElementById(errorID).removeChild(document.getElementById(errorID).firstChild);
	}
}	

function printErrorMessage(id, message){
	var errorMsg = document.createTextNode(message);
	document.getElementById(id).appendChild(errorMsg);
}