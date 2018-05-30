
var SubmitLock	= false;
var LastLogin	= '';
function Submit(LoginControl, PasswordInput, MessageContainer) {
	
	try {
		
		if (SubmitLock)
			return;
		
		SubmitLock = true;
		
		AlertInlineInsert(MessageContainer, 'fa fa-spinner fa-spin w3-xxlarge', 'Загрузка');
		
		//var Login		= document.getElementById(LoginInputID).innerHTML.trim();
		//var Password	= document.getElementById(PasswordInputID).innerHTML.trim();
		
		var Login		= LoginControl.SelectedText;
		var Password	= PasswordInput.value.trim();
		
		//if (!Login || !Password)
		if (!Login)
			throw new Error('Заполните все поля.');
		
		var DataObject = {
			"Login"			: Login
			, "Password"	: Password
		};
		
		var CallBackError = function(Message) { SubmitFail(MessageContainer, Message); };
		
		AjaxSendObject(window.location.href, DataObject, SubmitSuccessful, CallBackError, CallBackError);
		
	} catch (ex) {
		AlertInlineInsert(MessageContainer, 'fa fa-warning w3-xxlarge', ex.message);
		SubmitLock = false;
	}
	
}

function SubmitSuccessful(DataObject) {
	//SetCookie('Login', LastLogin);
	
	//
	// Save login
	//
	
	if (localStorage && LoginComboboxEditControl.SelectedText) {
		
		var Cl = LoginComboboxEditControl.Collection;
		var IsExist = false;
		for (var i = 0; i < LoginComboboxEditControl.Collection.length; i++) {
			var cli = LoginComboboxEditControl.Collection[i];
			if (cli.Text == LoginComboboxEditControl.SelectedText) {
				IsExist = true;
				break;
			}
		}
		if (!IsExist) {
			Cl.push({ "Text" : LoginComboboxEditControl.SelectedText });
			localStorage.setItem("Logins", JSON.stringify(Cl));
		}
		localStorage.setItem("LastLogin", LoginComboboxEditControl.SelectedText);
	}
	
//	var LoginsStr		= localStorage 	? localStorage.getItem("Logins") 	: null;
//	var LastLogin		= localStorage 	? localStorage.getItem("LastLogin")	: '';
	
	//
	// Redirect
	//
	
	window.location.href = DataObject.Data;
}

function SubmitFail(MessageContainer, Message) {
	AlertInlineInsert(MessageContainer, 'fa fa-warning w3-xxlarge', Message);
	SubmitLock = false;
}

function KeyEnterHandler(event, LoginControl, Password, MessageContainer, SubButton) {
	if (event.keyCode == 13) {
		//SubButton.focus();
		Submit(LoginControl, Password, MessageContainer);
	}	
}