/**
 * File : addUser.js
 * 
 * This file contain the validation of add user form
 * 
 * Using validation plugin : jquery.validate.js
 * 
 * @author Kishor Mali
 */

//return previos page
function cancel(url) {
	if (url == '')
		window.history.back();
	else
		location.href = url + 'userListing';
}

$(document).ready(function(){

	
	//var addUserForm = $("#addUser");

	//var validator = addUserForm.validate({
/*
		rules:{
			fname :{ required : true },
//			email : { required : true, email : true, remote : { url : baseURL + "checkEmailExists", type :"post"} },
			email : { required : true, email : false, remote : { url : baseURL + "checkEmailExists", type :"post"} },
			password : { required : true },
			cpassword : {required : true, equalTo: "#password"},
//			mobile : { required : true, digits : true },
			role : { required : true, selected : true}
		},
		messages:{
			fname :{ required : decodeURIComponent(escape('?????????20?????????????????')) },
			email : { required : "?????",remote : "???20??????????" },
			password : { required : "????????6-20??" },
			cpassword : {required : "?????", equalTo: "??6-20????????????????????" },
//			mobile : { required : "This field is required", digits : "Please enter numbers only" },
			role : { required : "???????????", selected : "???????????" }
		}
 */
	//});

});
