// Code goes here
var app = angular.module('myApp', ['ngResource', 'ngRoute','ngSanitize', '720kb.datepicker', 'ui.select2','selectize']);

app.config(function ($routeProvider) {
	console.log($routeProvider);
	
	  $routeProvider
	    .when('/index.php/sale/angret/:componentId',
	    {
	      templateUrl: "hello.html",
	      controller: "returnController"
	    })
	  
	    
	});

app.service('confService',function () {
    this.getAll ='getAll';
    this.add = 'modify';
    this.modify = 'modify';
    this.delete = 'delete';
    this.get = 'get';
    this.add = 'add';
    this.getByFilter = 'getByFilter';
    this.component = {};
    this.component.item = 'item';
    this.component.transaction = 'transaction';
    this.component.customer = 'customer';
    this.component.inventory = 'inventory';
    
    
    this.line1="Azad Footware";
    this.line2="Registration No. 39342";
    this.line3="61, Station Road, Mymensingh";
    this.line4="Sherpur branch - Roghunath Bazaar, Sherpur, M-01912895918";
    this.line5="";
    
});

app.service('saleService', function ($resource, $q) {
	
	var saleResource, delay ;
	var restBase = "../rest/";
			
	this.postObject = function (obj, component) {
		
		saleResource = $resource(restBase + component, {}, {
			postObject: { method: 'POST'}
		});
		
        delay = $q.defer();
        saleResource.postObject(obj, function (data) {
            delay.resolve(data);
        }, function () {
        	//TODO: need to localize the this message
            delay.reject('Unable to fetch..');
        });
        return delay.promise;
    };
});

app.service('customerService', function ($resource, $q) {
	
	var saleResource, delay ;
	var restBase = "../rest/";
			
	this.postObject = function (obj, component) {
		
		saleResource = $resource(restBase + component, {}, {
			postObject: { method: 'POST'}
		});
		
        delay = $q.defer();
        saleResource.postObject(obj, function (data) {
            delay.resolve(data);
        }, function () {
        	//TODO: need to localize the this message
            delay.reject('Unable to fetch..');
        });
        return delay.promise;
    };
});

app.service('itemService', function ($resource, $q) {
	
	var saleResource, delay ;
	var restBase = "../rest/";
			
	this.postObject = function (obj, component) {
		
		saleResource = $resource(restBase + component, {}, {
			postObject: { method: 'POST'}
		});
		
        delay = $q.defer();
        saleResource.postObject(obj, function (data) {
            delay.resolve(data);
        }, function () {
        	//TODO: need to localize the this message
            delay.reject('Unable to fetch..');
        });
        return delay.promise;
    };
});


app.service('accountService', function ($resource, $q) {
	
	var saleResource, delay ;
	var restUrl = "../rest/account";
	
	saleResource = $resource(restUrl, {}, {
		postObject: { method: 'POST'}
	});
			
	this.postObject = function (obj) {

        delay = $q.defer();
        saleResource.postObject(obj, function (data) {
            delay.resolve(data);
        }, function () {
        	//TODO: need to localize the this message
            delay.reject('Unable to fetch..');
        });
        return delay.promise;
    };
});
app.controller('saleController', function ($scope, saleService, customerService, itemService, accountService, confService) {

    $scope.drinks = [];

    $scope.foods = [];

    $scope.order = [];
    $scope.new = {};
    $scope.totOrders = 0;
    $scope.tmpCode = '';
    $scope.paidAmount = function(){
    	var total = 0.0;
    	angular.forEach($scope.accounts, function(acc){
    		total -= -acc.amount;
    	} );
    	
    	return total;
    };
    $scope.itemId = {};
    $scope.transaction = {};
    $scope.items = [];
    $scope.customers = [];
    $scope.customer = 2;
    $scope.accounts = [];
    $scope.showAll = false;
    $scope.discount=0.0;
    $scope.receipt = {};
    $scope.receipt.line1 = confService.line1;
    $scope.receipt.line2 = confService.line2;
    $scope.receipt.line3 = confService.line3;
    $scope.receipt.line4 = confService.line4;
    $scope.receipt.line5 = confService.line5;
    $scope.selectedItem = {};
    
    function isEmpty(obj) {
    for(var prop in obj) {
        if(obj.hasOwnProperty(prop))
            return false;
    }

    return true;
}

    var url = window.location.protocol + "://" + window.location.host + "/" + window.location.pathname;
    $scope.dateMillis = function(){
    	return Math.floor(Date.now() / 1000);
    };
    $scope.getDate = function () {
        var today = new Date();
        var mm = today.getMonth() + 1;
        var dd = today.getDate();
        var yyyy = today.getFullYear();
        
        if(dd<10)
        	dd = "0"+dd;
        if(mm<10)
        	 mm = "0"+mm;

        var date = yyyy + "-" + mm + "-" + dd

        return date
    };
    $scope.invoiceNo = 0;
    $scope.tdate=$scope.getDate();
    $scope.month = function(){
    	var res = $scope.tdate.split(" "); 
    	
    	return res[1];
    }
    $scope.singleConfig = {
    create: true,
    valueField: 'componentId',
    labelField: 'itemName',
    searchField: ['itemName'],
    sortField: 'text',
    maxItems: 1,
    onChange: function(id) {
	for (index = 0, len = $scope.items.length; index < len; ++index) {
		    if( $scope.items[index].componentId==id){
		   	$scope.selectedItem = $scope.items[index] ;
		   	 break;
		    }
		}
    },
  }
  
  $scope.chosenProduct = '0';
    $scope.year = function(){
    	var res = $scope.tdate.split(" "); 
    	
    	return res[0];
    }

    $scope.addToOrder = function (item, qty) {
        var flag = 0;
        if ($scope.order.length > 0) {
            for (var i = 0; i < $scope.order.length; i++) {
                if (item.componentId === $scope.order[i].componentId) {
                    item.qty += qty;
                    flag = 1;
                    break;
                }
            }
            if (flag === 0) {
                item.qty = 1;
            }
            if (item.qty < 2) {
                $scope.order.push(item);
            }
        } else {
            item.qty = qty;
            $scope.order.push(item);
        }
        
        
        userInfo = null;
		var Obj = {
				operation : confService.get,
				//loginBean : userInfo
				componentId:item.componentId
	    };
		//Obj.componentId = $routeParams.componentId;
	    promis = saleService.postObject(Obj, confService.component.inventory);
	    promis.then(function(data) {
			if (!data.success) {
				alert('load inventory unsuccessful, please try again.');
				return;
			}
			console.log('Inventory : ');
			console.log(data.data);
			if(data.data.pquantity - data.data.squantity < item.qty){
				if(data.data.pquantity - data.data.squantity<1)
					$scope.removeItem(item);
				else
					item.qty = data.data.pquantity - data.data.squantity;
				alert(item.itemName+' is not available....');
			}
		
	    });
        
        
    };

    $scope.removeOneEntity = function (item) {
        for (var i = 0; i < $scope.order.length; i++) {
            if (item.id === $scope.order[i].id) {
                item.qty -= 1;
                if (item.qty === 0) {
                    $scope.order.splice(i, 1);
                }
            }
        }
    };

    $scope.removeItem = function (item) {
        for (var i = 0; i < $scope.order.length; i++) {
            if (item.componentId === $scope.order[i].componentId) {
                $scope.order.splice(i, 1);
            }
        }
    };

    $scope.getTotal = function () {
        var tot = 0;
        for (var i = 0; i < $scope.order.length; i++) {
            tot += ($scope.order[i].salePrice * $scope.order[i].qty)
        }
        return tot;
    };
    
    $scope.netAmount = function(){
    	return $scope.getTotal() - $scope.discount;
    }

    $scope.clearOrder = function () {
        $scope.order = [];
        $scope.resetAccounts();
        $scope.transaction = {};
    };
    
    var updateTransaction = function(){
    	$scope.transaction.transaction = {description:'', tdate:$scope.tdate, month:$scope.month, year:$scope.year, type:'SALES'};
    	$scope.transaction.details = [];
    	angular.forEach($scope.order, function(item){
    		var detail = {userId:$scope.customer, itemId:item.componentId, accountId:13,type:-1,quantity:item.qty, unitPrice:item.salePrice};
    		 $scope.transaction.details.push(detail);
    	});
    	
    	if($scope.discount>0){
    		var detail = {userId:$scope.customer, itemId:1, accountId:9,type:1,quantity:$scope.getTotal() - $scope.netAmount(), unitPrice:1};
   		 	$scope.transaction.details.push(detail);
    	}
    	
    	angular.forEach($scope.accounts, function(acc){
    		if(acc.amount > 0){
    			var detail = {userId:1, itemId:1, accountId:acc.componentId, type:1,quantity:acc.amount, unitPrice:1};
    			$scope.transaction.details.push(detail);
    		}
    	});
    }

    $scope.checkout = function (index) {
    	if($scope.paidAmount() != $scope.netAmount())
    	{
    		alert('Paid amount must be equal to net amount!');
    		return;
    	}
    	
    	//alert(angular.equals($scope.customer, {}));
  
    	if($scope.customer == undefined || $scope.customer == null )
    	{
    		alert('You must select a customer!');
    		return;
    	}
    	updateTransaction();
    	
    	userInfo = null;
		var Obj = {
				operation : confService.add,
				//loginBean : userInfo
				data:$scope.transaction
	    };
		//Obj.componentId = $routeParams.componentId;
	    promis = saleService.postObject(Obj, confService.component.transaction);
	    promis.then(function(data) {
			if (!data.success) {
				alert('Checkout unsuccessful, please try again.');
				return;
			}
			
			$scope.invoiceNo = data.data.uniqueCode;
			document.getElementById('invNo').innerHTML = $scope.invoiceNo;
			//alert($scope.invoiceNo);
			 //alert($scope.getDate() + " - Order Number: " + ($scope.totOrders+1) + "\n\nOrder amount: $" + $scope.getTotal().toFixed(2) + "\n\nPayment received. Thanks.");
			 printDiv('printDiv');
			 //$scope.order = [];
			 $scope.clearOrder();
		     $scope.totOrders += 1;
		
	    });
    	
    	
       
    };

    $scope.addNewItem = function (item) {
        if (item.category === "Drinks") {
            item.id = $scope.drinks.length + $scope.foods.length
            $scope.drinks.push(item)
            $scope.new = []
            $('#myTab a[href="#drink"]').tab('show')
        } else if (item.category === "Foods") {
            item.id = $scope.drinks.length + $scope.foods.length
            $scope.foods.push(item)
            $scope.new = []
            $('#myTab a[href="#food"]').tab('show')
        }
    };
    
    $scope.addByCode = function(){
    	
    	for (i = 0; i < $scope.items.length; i++) {
    		if($scope.items[i].uniqueCode == $scope.tmpCode){
    			console.log($scope.items[i].itemName);
    			$scope.addToOrder($scope.items[i], 1);
    			$scope.tmpCode = '';
    			break;
    		}
    	}
    	
    };
    
    $scope.addItem = function(item, model){
    	$scope.addToOrder(item, 1);
    	$scope.itemId = {};
    };
    
    $scope.$watch("selectedItem",function(newValue){
    	
    	if(isEmpty(newValue)) return;
    	//var item = JSON.parse(newValue);
    	$scope.addToOrder(newValue, 1);
        console.log(newValue); 
    	//$scope.selectedItem= {};
  });
    

    $scope.resetAccounts = function(){
    	angular.forEach($scope.accounts, function(acc){
    		acc["amount"] = 0.0;
    	} );
    }
    
    
    
    var loadItems = function() {
		userInfo = null;
		var Obj = {
				operation : confService.getAll,
				//loginBean : userInfo
	    };
		//Obj.componentId = $routeParams.componentId;
	    promis = itemService.postObject(Obj, confService.component.item);
	    promis.then(function(data) {
			if (!data.success) {
				alert('Items load failed, please refresh / reload.');
				return;
			}
			
			$scope.items = data.data;
		
			//console.log('Loaded..');
			//+console.log(JSON.stringify($scope.items));
	    });
    };
    
    var loadCustomers = function() {
		userInfo = null;
		var Obj = {
				operation : confService.getByFilter,
				//loginBean : userInfo
				filter:{isCustomer:1}
				//loginBean : userInfo
	    };
		//Obj.componentId = $routeParams.componentId;
	    promis = customerService.postObject(Obj, confService.component.customer);
	    promis.then(function(data) {
			if (!data.success) {
				alert('Customers load failed, please refresh / reload.');
				return;
			}
			
			$scope.customers = data.data;
			//console.log('Loaded..');
			//console.log(JSON.stringify($scope.items));
	    });
    };
    
    var loadAccounts = function() {
		userInfo = null;
		var Obj = {
				operation : confService.getByFilter,
				//loginBean : userInfo
				filter:{category2:"CURRENT ASSET"}
	    };
		//Obj.componentId = $routeParams.componentId;
	    promis = accountService.postObject(Obj);
	    promis.then(function(data) {
			if (!data.success) {
				alert('Accounts load failed, please refresh / reload.');
				return;
			}
			
			$scope.accounts = data.data;
			$scope.resetAccounts();
			//console.log('Loaded..');
			//console.log(JSON.stringify($scope.accounts));
	    });
    };
    
    loadItems();
    loadCustomers();
    loadAccounts();
   
});


app.controller('purchaseController', function ($scope, saleService, customerService, itemService, accountService, confService) {

    $scope.drinks = [];

    $scope.foods = [];

    $scope.order = [];
    $scope.new = {};
    $scope.totOrders = 0;
    $scope.tmpCode = '';
    $scope.paidAmount = function(){
    	var total = 0.0;
    	angular.forEach($scope.accounts, function(acc){
    		total -= -acc.amount;
    	} );
    	
    	return total;
    };
    $scope.itemId = '';
    $scope.transaction = {};
    $scope.items = [];
    $scope.customers = [];
    $scope.customer = 3;
    $scope.accounts = [];
    $scope.showAll = false;
    $scope.discount=0.0;
    $scope.receipt = {};
    $scope.receipt.line1 = confService.line1;
    $scope.receipt.line2 = confService.line2;
    $scope.receipt.line3 = confService.line3;
    $scope.receipt.line4 = confService.line4;
    $scope.receipt.line5 = confService.line5;
    $scope.selectedItem = {};
       $scope.singleConfig = {
    create: true,
    valueField: 'componentId',
    labelField: 'itemName',
    searchField: ['itemName'],
    sortField: 'text',
    maxItems: 1,
    onChange: function(id) {
	for (index = 0, len = $scope.items.length; index < len; ++index) {
		    if( $scope.items[index].componentId==id){
		   	$scope.selectedItem = $scope.items[index] ;
		   	 break;
		    }
		}
    },
  }
  

    var url = window.location.protocol + "://" + window.location.host + "/" + window.location.pathname;
    $scope.dateMillis = function(){
    	return Math.floor(Date.now() / 1000);
    };
    $scope.getDate = function () {
        var today = new Date();
        var mm = today.getMonth() + 1;
        var dd = today.getDate();
        var yyyy = today.getFullYear();
        
        if(dd<10)
        	dd = "0"+dd;
        if(mm<10)
        	 mm = "0"+mm;

        var date = yyyy + "-" + mm + "-" + dd

        return date
    };
    $scope.invoiceNo = 0;
    $scope.tdate=$scope.getDate();
    $scope.month = function(){
    	var res = $scope.tdate.split(" "); 
    	
    	return res[1];
    }
    
        function isEmpty(obj) {
    for(var prop in obj) {
        if(obj.hasOwnProperty(prop))
            return false;
    }

    return true;
}
    
    $scope.year = function(){
    	var res = $scope.tdate.split(" "); 
    	
    	return res[0];
    }

    $scope.addToOrder = function (item, qty) {
        var flag = 0;
        if ($scope.order.length > 0) {
            for (var i = 0; i < $scope.order.length; i++) {
                if (item.componentId === $scope.order[i].componentId) {
                    item.qty += qty;
                    flag = 1;
                    break;
                }
            }
            if (flag === 0) {
                item.qty = 1;
            }
            if (item.qty < 2) {
                $scope.order.push(item);
            }
        } else {
            item.qty = qty;
            $scope.order.push(item);
        }
    };

    $scope.removeOneEntity = function (item) {
        for (var i = 0; i < $scope.order.length; i++) {
            if (item.id === $scope.order[i].id) {
                item.qty -= 1;
                if (item.qty === 0) {
                    $scope.order.splice(i, 1);
                }
            }
        }
    };

    $scope.removeItem = function (item) {
        for (var i = 0; i < $scope.order.length; i++) {
            if (item.componentId === $scope.order[i].componentId) {
                $scope.order.splice(i, 1);
            }
        }
    };

    $scope.getTotal = function () {
        var tot = 0;
        for (var i = 0; i < $scope.order.length; i++) {
            tot += ($scope.order[i].purchasePrice * $scope.order[i].qty)
        }
        return tot;
    };
    
    $scope.netAmount = function(){
    	return $scope.getTotal() - $scope.discount*$scope.getTotal()/100;
    }

    $scope.clearOrder = function () {
        $scope.order = [];
        $scope.resetAccounts();
        $scope.transaction = {};
    };
    
    var updateTransaction = function(){
    	$scope.transaction.transaction = {description:'', tdate:$scope.tdate, month:$scope.month, year:$scope.year, type:'PURCHASE'};
    	$scope.transaction.details = [];
    	angular.forEach($scope.order, function(item){
    		var detail = {userId:$scope.customer, itemId:item.componentId, accountId:12,type:1,quantity:item.qty, unitPrice:item.purchasePrice};
    		 $scope.transaction.details.push(detail);
    		 if($scope.discount>0){
    			 detail = {userId:$scope.customer, itemId:item.componentId, accountId:8,type:-1,quantity:item.qty, unitPrice:item.purchasePrice*$scope.discount/100};
    			 $scope.transaction.details.push(detail);
    		 }
    	});
    	
    	angular.forEach($scope.accounts, function(acc){
    		if(acc.amount > 0){
    			var detail = {userId:1, itemId:1, accountId:acc.componentId, type:-1,quantity:acc.amount, unitPrice:1};
    			$scope.transaction.details.push(detail);
    		}
    	});
    }

    $scope.checkout = function (index) {
    	if($scope.paidAmount() != $scope.netAmount())
    	{
    		alert('Paid amount must be equal to net amount!');
    		return;
    	}
    	
    	//alert(angular.equals($scope.customer, {}));
  
    	if($scope.customer == undefined || $scope.customer == null)
    	{
    		alert('You mmust select a supplier!');
    		return;
    	}
    	updateTransaction();
    	
    	userInfo = null;
		var Obj = {
				operation : confService.add,
				//loginBean : userInfo
				data:$scope.transaction
	    };
		//Obj.componentId = $routeParams.componentId;
	    promis = saleService.postObject(Obj, confService.component.transaction);
	    promis.then(function(data) {
			if (!data.success) {
				alert('Checkout unsuccessful, please try again.');
				return;
			}
			
			$scope.invoiceNo = data.data;
			
			 //alert($scope.getDate() + " - Order Number: " + ($scope.totOrders+1) + "\n\nOrder amount: $" + $scope.getTotal().toFixed(2) + "\n\nPayment received. Thanks.");
			 //printDiv('printDiv');
			 //$scope.order = [];
			 $scope.clearOrder();
		     $scope.totOrders += 1;
		     
		     alert('Payment successful');
		
	    });
    	
    	
       
    };

    $scope.addNewItem = function (item) {
        if (item.category === "Drinks") {
            item.id = $scope.drinks.length + $scope.foods.length
            $scope.drinks.push(item)
            $scope.new = []
            $('#myTab a[href="#drink"]').tab('show')
        } else if (item.category === "Foods") {
            item.id = $scope.drinks.length + $scope.foods.length
            $scope.foods.push(item)
            $scope.new = []
            $('#myTab a[href="#food"]').tab('show')
        }
    };
    
    $scope.addByCode = function(){
    	for (i = 0; i < $scope.items.length; i++) {
    		if($scope.items[i].uniqueCode == $scope.tmpCode){
    			console.log($scope.items[i].itemName);
    			$scope.addToOrder($scope.items[i], 1);
    			$scope.tmpCode = '';
    			break;
    		}
  
	}
/*
    	angular.forEach($scope.items, function(item){
    		if(item.uniqueCode==$scope.tmpCode){
    			console.log(item.itemName);
    			$scope.addToOrder(item, 1);
    			$scope.tmpCode = '';
    			return;
    		}
    	} );*/
    	
    };
    
    $scope.addItem = function(item, model){
    	$scope.addToOrder(item, 1);
    	console.log(item); 
    	$scope.itemId = {};
    };
    

    
     $scope.$watch("selectedItem",function(newValue){
    	
    	if(isEmpty(newValue)) return;
    	//var item = JSON.parse(newValue);
    	$scope.addToOrder(newValue, 1);
        console.log(newValue); 
    	//$scope.selectedItem= {};
  });
    
    $scope.updateCustomer = function(item, model){
    	$scope.customer = item;
    }
    
    $scope.resetAccounts = function(){
    	angular.forEach($scope.accounts, function(acc){
    		acc["amount"] = 0.0;
    	} );
    }
    
    
    
    var loadItems = function(tryCount) {
		userInfo = null;
		var Obj = {
				operation : confService.getAll,
				//loginBean : userInfo
	    };
		//Obj.componentId = $routeParams.componentId;
	    promis = itemService.postObject(Obj, confService.component.item);
	    promis.then(function(data) {
			if (!data.success) {
				if(tryCount<3){
					loadAccounts(tryCount+1);
					return;
				}
				alert('Items load failed, please refresh / reload.');
				return;
			}
			
			$scope.items = data.data;
		
			//console.log('Loaded..');
			//+console.log(JSON.stringify($scope.items));
	    });
    };
    
    var loadCustomers = function(tryCount) {
		userInfo = null;
		var Obj = {
				operation : confService.getByFilter,
				//loginBean : userInfo
				filter:{isSupplier:1}
				//loginBean : userInfo
	    };
		//Obj.componentId = $routeParams.componentId;
	    promis = customerService.postObject(Obj, confService.component.customer);
	    promis.then(function(data) {
			if (!data.success) {
				if(tryCount<3){
					loadAccounts(tryCount+1);
					return;
				}
				alert('Customer load failed, please refresh / reload.');
				return;
			}
			
			$scope.customers = data.data;
			
			//console.log('Loaded..');
			//console.log(JSON.stringify($scope.items));
	    });
    };
    
    var loadAccounts = function(tryCount) {
		userInfo = null;
		var Obj = {
				operation : confService.getByFilter,
				//loginBean : userInfo
				filter:{category2:"CURRENT ASSET"}
	    };
		//Obj.componentId = $routeParams.componentId;
	    promis = accountService.postObject(Obj);
	    promis.then(function(data) {
			if (!data.success) {
				if(tryCount<3){
					loadAccounts(tryCount+1);
					return;
				}
				alert('Accounts load failed, please refresh / reload.');
				return;
			}
			
			$scope.accounts = data.data;
			$scope.resetAccounts();
			//console.log('Loaded..');
			//console.log(JSON.stringify($scope.accounts));
	    });
    };
    
    loadItems(0);
    loadCustomers(0);
    loadAccounts(0);
   
});




app.controller('returnController', function ($routeParams, $scope, saleService, customerService, itemService, accountService, confService) {

    $scope.order = [];
    $scope.new = {};
    $scope.totOrders = 0;
    $scope.tmpCode = '';
    $scope.paidAmount = function(){
    	var total = 0.0;
    	angular.forEach($scope.accounts, function(acc){
    		total -= -acc.amount;
    	} );
    	
    	return total;
    };
    $scope.itemId = '';
    $scope.transaction = {};
    $scope.items = [];
    $scope.customers = [];
    $scope.customer = 3;
    $scope.accounts = [];
    $scope.showAll = false;
    $scope.discount=0.0;
    $scope.receipt = {};
    $scope.receipt.line1 = confService.line1;
    $scope.receipt.line2 = confService.line2;
    $scope.receipt.line3 = confService.line3;
    $scope.receipt.line4 = confService.line4;
    $scope.receipt.line5 = confService.line5;  

    var url = window.location.protocol + "://" + window.location.host + "/" + window.location.pathname;
    $scope.dateMillis = function(){
    	return Math.floor(Date.now() / 1000);
    };
    $scope.getDate = function () {
        var today = new Date();
        var mm = today.getMonth() + 1;
        var dd = today.getDate();
        var yyyy = today.getFullYear();
        
        if(dd<10)
        	dd = "0"+dd;
        if(mm<10)
        	 mm = "0"+mm;

        var date = yyyy + "-" + mm + "-" + dd

        return date
    };
    $scope.invoiceNo = 0;
    $scope.tdate=$scope.getDate();
    $scope.month = function(){
    	var res = $scope.tdate.split(" "); 
    	
    	return res[1];
    }
    
        function isEmpty(obj) {
    for(var prop in obj) {
        if(obj.hasOwnProperty(prop))
            return false;
    }

    return true;
}
    
    $scope.year = function(){
    	var res = $scope.tdate.split(" "); 
    	
    	return res[0];
    }

    $scope.addToOrder = function (item, qty) {
        var flag = 0;
        if ($scope.order.length > 0) {
            for (var i = 0; i < $scope.order.length; i++) {
                if (item.componentId === $scope.order[i].componentId) {
                    item.qty += qty;
                    flag = 1;
                    break;
                }
            }
            if (flag === 0) {
                item.qty = 1;
            }
            if (item.qty < 2) {
                $scope.order.push(item);
            }
        } else {
            item.qty = qty;
            $scope.order.push(item);
        }
    };


    $scope.getTotal = function () {
        var tot = 0;
        for (var i = 0; i < $scope.order.length; i++) {
            tot += ($scope.order[i].purchasePrice * $scope.order[i].qty)
        }
        return tot;
    };
    
    $scope.netAmount = function(){
    	return $scope.getTotal() - $scope.discount*$scope.getTotal()/100;
    }

    $scope.clearOrder = function () {
        $scope.order = [];
        $scope.resetAccounts();
        $scope.transaction = {};
    };
    
    var updateTransaction = function(){
    	$scope.transaction.transaction = {description:'', tdate:$scope.tdate, month:$scope.month, year:$scope.year, type:$scope.transaction.type+'-RETURN'};
    	for(var idx = 0;idx<$scope.transaction.details.length;idx++){
    		$scope.transaction.details[idx].type = -($scope.transaction.details[idx].type);
    		$scope.transaction.details[idx].componentId = null;
    	}
    
   
    }
    
    $scope.doReturn = function () {

    	updateTransaction();
    	
    	userInfo = null;
		var Obj = {
				operation : confService.add,
				//loginBean : userInfo
				data:$scope.transaction
	    };
		//Obj.componentId = $routeParams.componentId;
	    promis = saleService.postObject(Obj, confService.component.transaction);
	    promis.then(function(data) {
			if (!data.success) {
				alert('Checkout unsuccessful, please try again.');
				return;
			}
			
			$scope.invoiceNo = data.data;
			
	
			 $scope.clearOrder();
		     $scope.totOrders += 1;
		     
		     alert('Return successful');
		
	    });
    	
    	
       
    };
    
    $scope.resetAccounts = function(){
    	//console.log('as');
    	
    };
    
    $scope.getInvoice = function(){
    	
    	userInfo = null;
		var Obj = {
				operation : confService.getByFilter,
				//loginBean : userInfo
				filter:{uniqueCode:$scope.tmpCode}
	    };
		//Obj.componentId = $routeParams.componentId;
	    promis = saleService.postObject(Obj, confService.component.transaction);
	    promis.then(function(data) {
			if (!data.success) {
				alert('Load failed....');
				return;
			}
			
			
			console.log(JSON.stringify(data.data));
			
			if(data.data.length>0){
				$scope.transaction = data.data[0];
				//$scope.tdate = data.data[0].tdate;
				angular.forEach(data.data[0].details, function(detail){
					if(detail.accountId==12 ||detail.accountId==13){
						var iObj = {
								operation : confService.get,
								//loginBean : userInfo
								componentId:detail.itemId
					    };
						var itm = {qty:detail.quantity, purchasePrice:detail.unitPrice};
						//Obj.componentId = $routeParams.componentId;
					    promis = itemService.postObject(iObj, confService.component.item);
					    promis.then(function(itemdata) {
							if (!itemdata.success) {
								alert('Load failed....');
								return;
							}
							itm.itemName = itemdata.itemName;
							$scope.addToOrder(itm, detail.quantity);
					    });
					}
				});
				
			}
			
		
	    });
    	
    };
    
    $scope.addItem = function(item, model){
    	$scope.addToOrder(item, 1);
    	console.log(item); 
    	$scope.itemId = {};
    };
    

    
    $scope.updateCustomer = function(item, model){
    	$scope.customer = item;
    }

    var loadItems = function(tryCount) {
		userInfo = null;
		var Obj = {
				operation : confService.getAll,
				//loginBean : userInfo
	    };
		//Obj.componentId = $routeParams.componentId;
	    promis = itemService.postObject(Obj, confService.component.item);
	    promis.then(function(data) {
			if (!data.success) {
				if(tryCount<3){
					loadAccounts(tryCount+1);
					return;
				}else{
					alert('Items load failed, please refresh / reload.');
					return;
				}
			}
			
			$scope.items = data.data;
		
			//console.log('Loaded..');
			//+console.log(JSON.stringify($scope.items));
	    });
    };
    
    var loadCustomers = function(tryCount) {
		userInfo = null;
		var Obj = {
				operation : confService.getByFilter,
				//loginBean : userInfo
				filter:{isSupplier:1}
				//loginBean : userInfo
	    };
		//Obj.componentId = $routeParams.componentId;
	    promis = customerService.postObject(Obj, confService.component.customer);
	    promis.then(function(data) {
			if (!data.success) {
				if(tryCount<3){
					loadAccounts(tryCount+1);
					return;
				}else{
					alert('Customer load failed, please refresh / reload.');
					return;
				}
			}
			
			$scope.customers = data.data;
			
			//console.log('Loaded..');
			//console.log(JSON.stringify($scope.items));
	    });
    };
    
    var loadAccounts = function(tryCount) {
		userInfo = null;
		var Obj = {
				operation : confService.getByFilter,
				//loginBean : userInfo
				filter:{category2:"CURRENT ASSET"}
	    };
		//Obj.componentId = $routeParams.componentId;
	    promis = accountService.postObject(Obj);
	    promis.then(function(data) {
			if (!data.success) {
				if(tryCount<3){
					loadAccounts(tryCount+1);
					return;
				}
				//alert('Accounts load failed, please refresh / reload.');
				return;
			}
			
			$scope.accounts = data.data;
			$scope.resetAccounts();
			//console.log('Loaded..');
			//console.log(JSON.stringify($scope.accounts));
	    });
    };
    
    var loadTransaction = function(tryCount) {
    	if ($routeParams.componentId == undefined || $routeParams.componentId == null || $routeParams.componentId.length == 0) {
			return;
		}
 	
		userInfo = null;
		var Obj = {
				operation : confService.get,
				//loginBean : userInfo
				//componentId:{componentId:$routeParams.componentId}
	    };
		Obj.componentId = $routeParams.componentId;
	    promis = accountService.postObject(Obj, confService.component.transaction);
	    promis.then(function(data) {
			if (!data.success) {
				if(tryCount<3){
					loadTransaction(tryCount+1);
					return;
				}
				//alert('Accounts load failed, please refresh / reload.');
				return;
			}
			
			//$scope.accounts = data.data;
			//$scope.resetAccounts();
			console.log(data.data);
			//console.log(JSON.stringify($scope.accounts));
	    });
    };
    
    loadItems(0);
    loadCustomers(0);
    loadAccounts(0);
    loadTransaction(0);
    
   
});