var user = parent.document.URL.substring(parent.document.URL.indexOf('?'), parent.document.URL.length);
user = user.split('=')[1];

if (user == undefined) {
	window.location = 'buyonline.htm';
}

document.getElementById('listing').href = 'listing.htm?user='+user;
document.getElementById('processing').href = 'processing.htm?user='+user;
document.getElementById('logout').href = 'logout.htm?user='+user;

var table = document.querySelector("#itemTable tbody");

document.getElementById('btnProcess').addEventListener("click", function(){
	console.log('Process');
	processItems();
});	

getItems();

function getItems() {
	var http = new XMLHttpRequest();
	var url = 'itemsP.php';

	http.open('GET', url, true);
	//Send the proper header information along with the request
	http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		http.onreadystatechange = function() {//Call a function when the state changes.
			if(http.readyState == 4 && http.status == 200) {
				items = http.responseText.split('\n');
				displayItems();
			}
		}
		http.send();
	}

	var itemsArr = [];

	function displayItems() {
	//delete old rows
	while(table.hasChildNodes()){
		table.removeChild(table.firstChild);
	}
	itemsArr = [];
	for (var i = 0; i < items.length; i++) {
		if (items[i] == '') { continue; }
		var item = items[i].split(",");
		var tr = table.insertRow(i);
			//k = element, j = index					
			item.forEach((k, j) => { // Keys from object represent th.innerHTML
				var cell = tr.insertCell(j);
				cell.innerHTML = k; // Assign object values to cells   	
			});			
			itemsArr.push(item);
		}
		console.log(itemsArr);
	}

	function processItems() {
		for (var i = 0; i < itemsArr.length; i++) {
			console.log('Item: ' + itemsArr[i][0]);
			var http = new XMLHttpRequest();
			var url = 'processItem.php';
			var params = 'item='+ itemsArr[i][0];

			http.open('POST', url, false);
			//Send the proper header information along with the request
			http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			http.onreadystatechange = function() {
				if(http.readyState == 4 && http.status == 200) {
					console.log(http.responseText);
					if (i == itemsArr.length-1) {
						removeSoldOutItems();
					}
				}
			}
			http.send(params);
		}
	}



	function removeSoldOutItems() {
		for (var i = 0; i < itemsArr.length; i++) {
			var http = new XMLHttpRequest();
			var url = 'removeSoldOutItems.php';
			var params = 'item='+ itemsArr[i][0];

			http.open('POST', url, false);
			//Send the proper header information along with the request
			http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			http.onreadystatechange = function() {
				if(http.readyState == 4 && http.status == 200) {
					console.log(http.responseText);
					if (i == itemsArr.length-1) {
						getItems();
					}
				}
			}
			http.send(params);
		}		
	}