window.onload = function() {
	//add Event
	clickForAddEvent();
	clickForClose();

};
document.addEventListener('DOMContentLoaded', function(){
	var urlParams = getUrlParams();
	console.log('http://'+location.hostname+'/main/createUser');
	//if we need reg form
	if (typeof urlParams.registration != "undefined") {
		hideContainer('authorization');
		showConatainer('registration');
	}

	//if we need login form
	if (typeof urlParams.login != "undefined") {
		hideContainer('registration');
		showConatainer('authorization');
	}

	document.forms['registrationForm'].onsubmit = function(e){
		e.preventDefault();
		registrationSubmit();
	};

	document.forms['authorizationForm'].onsubmit = function(e) {
		e.preventDefault();
		loginSubmit();
	};

	document.forms['addEventForm'].onsubmit = function(e) {
		e.preventDefault();
		addEventSubmit();
	};
});

function clickForAddEvent()
{
	var a = document.querySelectorAll('.dayMonth');

	//We search through all found elements and hang events on them
	[].forEach.call( a, function(el) {
		//hang events on
		el.onclick = function(e) {
			showConatainer('addEvent');
			var form = document.getElementById('addEventForm');
			var inputDate = document.querySelector('#addEventForm #startDate');
			data = this.dataset;
			inputDate.value = data.date;
			return false;
		}
	});
}
function clickForClose()
{
	var a = document.querySelectorAll('.closeLink');
	//We search through all found elements and hang events on them
	[].forEach.call( a, function(el) {
		//hang events on
		el.onclick = function(e) {
			hideContainer(this.parentNode.parentNode.getAttribute('id'));
			return false;
		}
	});
}
function getXmlHttp() {
	var xmlhttp;
	try {
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
		try {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (E) {
			xmlhttp = false;
		}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
		xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}
function registrationSubmit()
{
	document.getElementById('registrationErrors').innerHTML = '';
	var form = document.getElementById('registrationForm');
	var jsonForm = toJSONString(form);
	console.log(jsonForm);

	var xmlhttp = getXmlHttp(); // create object XMLHTTP
	xmlhttp.open('POST', 'http://'+location.hostname, true); // Open asynchronous connection
	xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); // Sending the encoding
	xmlhttp.send('form='+jsonForm); // Sending a POST request
	xmlhttp.onreadystatechange = function() { // We are waiting for a response from the server
		if (xmlhttp.readyState == 4) { // The answer has come
			if(xmlhttp.status == 200) { // The server returned the code 200 (which is good)
				var res = JSON.parse(xmlhttp.responseText);
				if (typeof res.errors != 'undefined') {
					res.errors.forEach(logRegArrayErrorElements);
				} else {
					window.location = "http://"+location.hostname;
				}
			}
		}
	};
}

function loginSubmit()
{
	document.getElementById('authorizationErrors').innerHTML = '';
	var form = document.getElementById('authorizationForm');
	var jsonForm = toJSONString(form);
	var xmlhttp = getXmlHttp(); // create object XMLHTTP
	xmlhttp.open('POST', 'http://'+location.hostname, true); // Open asynchronous connection
	xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); // Sending the encoding
	xmlhttp.send('form='+jsonForm); // Sending a POST request
	xmlhttp.onreadystatechange = function() { // We are waiting for a response from the server
		if (xmlhttp.readyState == 4) { // The answer has come
			if(xmlhttp.status == 200) { // The server returned the code 200 (which is good)
				//console.log(xmlhttp.responseText);
				var res = JSON.parse(xmlhttp.responseText);
				if (typeof res.errors != 'undefined') {
					res.errors.forEach(logLoginArrayErrorElements);
				} else {
					window.location = "http://"+location.hostname;
				}
			}
		}
	};
}

function addEventSubmit()
{
	var form = document.getElementById('addEventForm');
	var jsonForm = toJSONString(form);
	var xmlhttp = getXmlHttp(); // create object XMLHTTP
	xmlhttp.open('POST', 'http://'+location.hostname+'/main/addEvent', true); // Open asynchronous connection
	xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); // Sending the encoding
	xmlhttp.send('formevent='+jsonForm); // Sending a POST request
	xmlhttp.onreadystatechange = function() { // We are waiting for a response from the server
		if (xmlhttp.readyState == 4) { // The answer has come
			if(xmlhttp.status == 200) { // The server returned the code 200 (which is good)
				console.log(xmlhttp.responseText);
				var res = JSON.parse(xmlhttp.responseText);
				console.log(res);
			}
		}
	};
}

function logRegArrayErrorElements(element, index, array) {
	document.getElementById('registrationErrors').innerHTML = document.getElementById('registrationErrors').innerHTML + element + "<br>";
}
function logLoginArrayErrorElements(element, index, array) {
	document.getElementById('authorizationErrors').innerHTML = document.getElementById('authorizationErrors').innerHTML + element + "<br>";
}

function toJSONString( form ) {
	var obj = {};
	var elements = form.querySelectorAll( "input, select, textarea" );
	for( var i = 0; i < elements.length; ++i ) {
		var element = elements[i];
		var name = element.name;
		var value = element.value;

		if( name ) {
			obj[ name ] = value;
		}
	}

	return JSON.stringify( obj );
}

function getUrlParams()
{
	var params = {};

	if (location.search) {
		var parts = location.search.substring(1).split('&');

		for (var i = 0; i < parts.length; i++) {
			var nv = parts[i].split('=');
			if (!nv[0]) continue;
			params[nv[0]] = nv[1] || true;
		}
	}

	return params;
}
function hideContainer(id) {
	document.getElementById(id).style.display = "none";
}
function showConatainer(id) {
	document.getElementById(id).style.display = "";
}






function Calendar3(id, year, month) {
	var Dlast = new Date(year,month+1,0).getDate(),
		D = new Date(year,month,Dlast),
		DNlast = D.getDay(),
		DNfirst = new Date(D.getFullYear(),D.getMonth(),1).getDay(),
		calendar = '<tr>',
		m = document.querySelector('#'+id+' option[value="' + D.getMonth() + '"]'),
		g = document.querySelector('#'+id+' input');

	if (DNfirst != 0) {
		for(var  i = 1; i < DNfirst; i++) calendar += '<td></td>';
	}else{
		for(var  i = 0; i < 6; i++) calendar += '<td></td>';
	}
	var day = '';
	var strMonth = '';
	for(var  i = 1; i <= Dlast; i++) {
		if (i < 10) {
			day = '0'+i;
		} else {
			day = i;
		}
		strMonth = month < 10 ? '0'+month : month;

		if (i == new Date().getDate() && D.getFullYear() == new Date().getFullYear() && D.getMonth() == new Date().getMonth()) {
			calendar += '<td class="today"><a href="" class="dayMonth" data-date="'+ D.getFullYear() + '-' + strMonth + '-' + day +'">' + i + '</a></td>';
		}else{
			if (  // список официальных праздников
			(i == 1 && D.getMonth() == 0 && ((D.getFullYear() > 1897 && D.getFullYear() < 1930) || D.getFullYear() > 1947)) || // Новый год
			(i == 2 && D.getMonth() == 0 && D.getFullYear() > 1992) || // Новый год
			((i == 3 || i == 4 || i == 5 || i == 6 || i == 8) && D.getMonth() == 0 && D.getFullYear() > 2004) || // Новый год
			(i == 7 && D.getMonth() == 0 && D.getFullYear() > 1990) || // Рождество Христово
			(i == 23 && D.getMonth() == 1 && D.getFullYear() > 2001) || // День защитника Отечества
			(i == 8 && D.getMonth() == 2 && D.getFullYear() > 1965) || // Международный женский день
			(i == 1 && D.getMonth() == 4 && D.getFullYear() > 1917) || // Праздник Весны и Труда
			(i == 9 && D.getMonth() == 4 && D.getFullYear() > 1964) || // День Победы
			(i == 12 && D.getMonth() == 5 && D.getFullYear() > 1990) || // День России (декларации о государственном суверенитете Российской Федерации ознаменовала окончательный Распад СССР)
			(i == 7 && D.getMonth() == 10 && (D.getFullYear() > 1926 && D.getFullYear() < 2005)) || // Октябрьская революция 1917 года
			(i == 8 && D.getMonth() == 10 && (D.getFullYear() > 1926 && D.getFullYear() < 1992)) || // Октябрьская революция 1917 года
			(i == 4 && D.getMonth() == 10 && D.getFullYear() > 2004) // День народного единства, который заменил Октябрьскую революцию 1917 года
			) {

				calendar += '<td class="holiday"><a href="#" class="dayMonth" data-date="'+ D.getFullYear() + '-' + strMonth + '-' + day +'">' + i+ '</a></td>';
			}else{
				calendar += '<td><a href="#" class="dayMonth" data-date="'+ D.getFullYear() + '-' + strMonth + '-' + day +'">' + i + '</a></td>';
			}
		}
		if (new Date(D.getFullYear(),D.getMonth(),i).getDay() == 0) {
			calendar += '<tr>';
		}
	}
	for(var  i = DNlast; i < 7; i++) calendar += '<td>&nbsp;</td>';
	document.querySelector('#'+id+' tbody').innerHTML = calendar;
	g.value = D.getFullYear();
	m.selected = true;
	if (document.querySelectorAll('#'+id+' tbody tr').length < 6) {
		document.querySelector('#'+id+' tbody').innerHTML += '<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
	}
	document.querySelector('#'+id+' option[value="' + new Date().getMonth() + '"]').style.color = 'rgb(220, 0, 0)'; // в выпадающем списке выделен текущий месяц
}
Calendar3("calendar3",new Date().getFullYear(),new Date().getMonth());
document.querySelector('#calendar3').onchange = function Kalendar3() {
	Calendar3("calendar3",document.querySelector('#calendar3 input').value,parseFloat(document.querySelector('#calendar3 select').options[document.querySelector('#calendar3 select').selectedIndex].value));
}