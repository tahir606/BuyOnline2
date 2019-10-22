
var user = parent.document.URL.substring(parent.document.URL.indexOf('?'), parent.document.URL.length);
user = user.split('=')[1];

document.getElementById('logout').href = 'logout.htm?user='+user;

var table = document.querySelector("#itemTable tbody");
var tableCart = document.querySelector("#cartTable tbody");

getItems();
registerItemToCart('1');

// time=setInterval(function(){
// 	//your code
// 	console.log('Interval');
// 	getItems();
// },5000);

var items;

function getItems() {
	var http = new XMLHttpRequest();
	var url = '../php/items.php';

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

function displayItems() {
	//delete old rows
	while(table.hasChildNodes()){
		table.removeChild(table.firstChild);
	}
	for (var i = 0; i < items.length; i++) {
		if (items[i] == '') { continue; }
			var item = items[i].split(",");
			var tr = table.insertRow(i);					
			item.forEach((k, j) => { // Keys from object represent th.innerHTML
				var cell = tr.insertCell(j);				
   				cell.innerHTML = k; // Assign object values to cells   
   			});
			var cell = tr.insertCell();
			cell.innerHTML = '<button id="rowBtn' + i +'">Add one to Cart</button>';

			document.getElementById('rowBtn'+i).addEventListener("click", function(){
				addToCart(this.id.substring(6));
			});
	}
}

var cartItems = [];

function addToCart(row) {
	var item = items[row].split(',')
	for (var i = 0; i < cartItems.length; i++) {
		if (cartItems[i].item == item[0]) {
			cartExistingItem(cartItems[i]);
			calculateTotal();
			return;
		}				
	} 
	cartNewItem(row);
	calculateTotal();	
}

//Adding to cart if the item is new
function cartNewItem(row) {
	//New Item
	var item = items[row].split(',');
	var tr = tableCart.insertRow();
	//itemNo
	var cellNo = tr.insertCell();
	cellNo.innerHTML = item[0];
	//itemPrice
	var cellPrice = tr.insertCell();
	cellPrice.innerHTML = item[3];
	//itemQty
	var cellQty = tr.insertCell();
	cellQty.id = 'row'+item[0];
	cellQty.innerHTML = '1';

	var cellBtn = tr.insertCell();
	cellBtn.innerHTML = '<button id="remBtn' + item[0] +'">Remove From Cart</button>';		

	cartItems.push({item: item[0],
		price: item[3],
		qty: 1});
}

//Adding to cart if the item already exists
function cartExistingItem(item) {
	for (var i = 0; i < cartItems.length; i++) {
		if (cartItems[i].item == item.item) {
			cartItems[i].qty = cartItems[i].qty + 1;
			document.getElementById('row'+item.item).innerHTML = cartItems[i].qty;
		}
	}
}

function calculateTotal() {
	var total = document.getElementById('total');
	var t = 0;
	for (var i = 0; i < cartItems.length; i++) {
		t = t + (cartItems[i].qty * cartItems[i].price);
	}
	total.innerHTML = '$' + t;
}

function registerItemToCart(item) {
	var http = new XMLHttpRequest();
	var url = '../php/additemtocart.php';
	var params = 'item='+ item;

	http.open('POST', url, true);
	//Send the proper header information along with the request
	http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		http.onreadystatechange = function() {//Call a function when the state changes.
		if(http.readyState == 4 && http.status == 200) {
			console.log(http.responseText);
		}
	}
	http.send(params);
}