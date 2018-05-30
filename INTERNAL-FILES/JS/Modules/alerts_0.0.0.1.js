
function DisplayAlert(Message, Header) {
	Header	= Header	|| "Сообщение";
	var MessageDiv = document.createElement("DIV");
	MessageDiv.innerHTML = '\
	<div class="w3-modal animate-opacity-quick" style="display: block;" >\
	    <div class="w3-modal-content">\
	      <div class="w3-container w3-theme-l1"> \
	        <span class="w3-button btn-modal-close w3-display-topright" onclick="this.parentElement.parentElement.parentElement.style.display=\'none\'">&times;</span>\
	        <h3>' + Header + '</h3>\
	      </div>\
	      <div class="w3-container">\
	        <p>' + Message + '</p>\
	      </div>\
	      <div class="w3-container w3-theme-l1">\
	        <p></p>\
	      </div>\
	    </div>\
	</div>\
	';
	document.body.appendChild(MessageDiv);
}

function DisplayBanAlert(Message, Header) {
	Header	= Header	|| "Сообщение";
	var MessageDiv = document.createElement("DIV");
	MessageDiv.innerHTML = '\
	<div class="w3-modal animate-opacity-quick" style="display: block;" >\
	    <div class="w3-modal-content">\
	      <div class="w3-container w3-theme-l1"> \
	        <h3>' + Header + '</h3>\
	      </div>\
	      <div class="w3-container">\
	        <p>' + Message + '</p>\
	      </div>\
	      <div class="w3-container w3-theme-l1">\
	        <p></p>\
	      </div>\
	    </div>\
	</div>\
	';
	document.body.appendChild(MessageDiv);
}

function DisplayDialogYesNo(Message, YesCallBack) {
	var DisplayDialogContainer = document.createElement("DIV");
	DisplayDialogContainer.innerHTML = '\
	<div class="w3-modal animate-opacity-quick" style="display: block;"	onclick="this.style.display=\'none\'">\
		<div class="w3-modal-content">\
	      <div class="w3-container w3-theme-l1"> \
	        <span class="w3-button btn-modal-close w3-display-topright">&times;</span>\
	        <h3>Сообщение</h3>\
	      </div>\
	      <div class="w3-container">\
	        <p>' + Message + '</p>\
	        <div style="margin: 0 0 20px 0; text-align: center">\
		        <button class="w3-btn w3-white w3-border w3-hover-theme" style="padding: 1em; width: 200px"\
							onclick="' + YesCallBack + '">\
					<i class="fa fa-check" style="font-size: 120%"></i> Да \
				</button>\
				<button class="w3-btn w3-white w3-border w3-hover-theme" style="padding: 1em; width: 200px">\
					<i class="fa fa-ban" style="font-size: 120%"></i> Нет \
				</button>\
	        </div>\
	      </div>\
	      <div class="w3-container w3-theme-l1">\
	        <p></p>\
	      </div>\
	    </div>\
	</div>\
	';
	document.body.appendChild(DisplayDialogContainer);
}


function AlertInlineInsert(Container, IconClass, Message, ContainerCSS, IconCSS, MessageClass, MessageCSS) {
	
	ContainerCSS	= ContainerCSS	|| 'margin: 10px;';
	IconClass		= IconClass 	|| 'fa fa-spinner fa-spin';
	IconCSS			= IconCSS		|| '';
	Message			= Message		|| '';
	MessageClass	= MessageClass	|| '';
	MessageCSS		= MessageCSS	|| '';
	
	var html = '\
	<div style="' + ContainerCSS + '" class="w3-animate-opacity">\
		<div class="w3-cell w3-cell-middle">\
			<i class="' + IconClass + '" style="' + IconCSS + '"></i>\
		</div>\
		<div class="w3-cell w3-cell-middle ' + MessageClass + '" style="width: 100%;padding-left: 20px; ' + MessageCSS + '">\
			' + Message + '\
		</div>\
	</div>\
	';
	
	if (html != Container.innerHTML)
		Container.innerHTML	= html; 
	
}

function AlertInlineUpdate(Element, Message) {
	Element.children[0].children[1].innerHTML = Message;
}

function AlertInlineDelete(Element) {
	Element.innerHTML = '';
}
