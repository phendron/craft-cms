$(document).ready(function(){



var secure_form = "login-form form"
var secure_form_el = $(secure_form);
var secure_form_submit_el = secure_form_el.find("input[type=submit]");
var secure_form_action = secure_form_el.find("input[name=action]");
var secure_form_error_el = $("error-msg");
secure_form_submit_el.on("click", function(e){
e.preventDefault();

data = secure_form_el.serialize();
$.post(secure_form_action.val(), data, function(rdata){
rdata = JSON.parse(rdata);
request_error=false;
if("error" in rdata){
for(var key in rdata["error"]){
var value = rdata["error"][key];
if(value == true){
request_error=true;
displayError(value);
break;
}
}
if(request_error == false){
secure_form_error_el.hide();
}
}

if("success" in rdata){
if(rdata["success"] == true){
window.location.replace(rdata["redirect"]);
} else {console.log("success: "+rdata);}
} else {console.log("no-key");}
});

});



function displayError(field=false){

error_msg = "An unexpected error occured.";

switch(field){
case "username":
error_msg="That username does not exist."
break;
default:
break;
}
secure_form_error_el.html(error_msg);
secure_form_error_el.show();
}

});
