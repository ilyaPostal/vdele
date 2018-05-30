
//
// Получение Cookie
//

function GetCookie(Key) {
  var matches = document.cookie.match(new RegExp(
    "(?:^|; )" + Key.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
  ));
  matches = matches ? decodeURIComponent(matches[1]) : undefined;
  return matches == "null" ? null : matches;
}

//
// Установка Cookie
//

function SetCookie(Key, Value) {
	document.cookie = Key + "=" + Value + "; path=/";
}