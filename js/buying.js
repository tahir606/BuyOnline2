
var user = parent.document.URL.substring(parent.document.URL.indexOf('?'), parent.document.URL.length);
user = user.split('=')[1];

document.getElementById('logout').href = 'logout.htm?user='+user;

var table = document.querySelector("#itemTable tbody");
var tableCart = document.querySelector("#cartTable tbody");

//Buttons event handlers
document.getElementById('btnConfirm').addEventListener("click", function(){
	confirmPurchase();
});
document.getElementById('btnCancel').addEventListener("click", function(){
	cancelPurchase();
});

getItems();
time=setInterval(function(){
	getItems();
},5000);

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
			//k = element, j = index					
			item.forEach((k, j) => { // Keys from object represent th.innerHTML
				var cell = tr.insertCell(j);
				//substring if displaying description
				if (j==2) {
					cell.innerHTML = k.substring(0,20);		
				} else {
				cell.innerHTML = k; // Assign object values to cells   	
			}			

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
				registerItemToCart(item[0]);
				return;
			}				
		} 
		cartNewItem(row);
		calculateTotal();	
		registerItemToCart(item[0]);
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

	//Done before Adding because length increase after
	document.getElementById('remBtn'+ item[0]).addEventListener("click", function(){
				// addToCart(this.id.substring(6));
				removeItemFromCart(this.id.substring(6), this);
			});

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

var infoMessage = document.getElementById('infoMessage');

function confirmPurchase() {
	for (var i = 0; i < cartItems.length; i++) {
			console.log('trying for: ' + cartItems[i].item);
			var http = new XMLHttpRequest();
			var url = '../php/confirmPurchase.php';
			var params = 'item='+ cartItems[i].item;

			http.open('POST', url, false);
			//Send the proper header information along with the request
			http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			http.onreadystatechange = function() {//Call a function when the state changes.
				if(http.readyState == 4 && http.status == 200) {
					console.log(http.responseText);
					if (i == cartItems.length-1) {
						infoMessage.innerHTML = "Your purchase has been confirmed and total amount due to pay is " + document.getElementById('total').innerHTML;
					}
				}
			}
			http.send(params);
		}

		//Clearing cart
		//Reference: https://stackoverflow.com/questions/7271490/delete-all-rows-in-an-html-table
		var new_tbody = document.createElement('tbody');
		tableCart.parentNode.replaceChild(new_tbody, tableCart)
		tableCart = document.querySelector("#cartTable tbody");
		cartItems = [];
		calculateTotal();
}

function cancelPurchase() {

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

	function removeItemFromCart(itemNo, btn) {
		console.log(itemNo);
		console.log(cartItems);
		var item;
		var index;
		//Getting Item
		for (var i = 
			0; i < cartItems.length; i++) {
			if(cartItems[i].item == itemNo) {
				item = cartItems[i];
				index = i;
				console.log(item);
				break;
			}
		}

		var http = new XMLHttpRequest();
		var url = '../php/removeItemFromCart.php';
		var params = 'item='+ item.item;

		http.open('POST', url, true);
		//Send the proper header information along with the request
		http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			http.onreadystatechange = function() {//Call a function when the state changes.
				if(http.readyState == 4 && http.status == 200) {
					console.log(http.responseText);
					// Code Reference From: 
					//https://stackoverflow.com/questions/13241005/add-delete-row-from-a-table
					var row = btn.parentNode.parentNode;
					row.parentNode.removeChild(row);

					cartItems.splice(index, 1);
					calculateTotal();
				}
			}
			http.send(params);
		}