<!DOCTYPE html>
<html >
<head>
    <title>POSPlus by NetSoft</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://code.angularjs.org/1.3.0-rc.2/angular.js"></script>
    <script src="https://code.angularjs.org/1.3.11/angular-route.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.0/angular-resource.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.3.9/angular-sanitize.js"></script>
    <script src="<?php echo base_url();?>assets/ang/js/app.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>assets/ang/js/selectize.js" type="text/javascript"></script>
    <script src="<?php echo base_url();?>assets/ang/js/angular-selectize.js" type="text/javascript"></script>
    <link rel="stylesheet" href="<?php echo base_url();?>assets/ang/css/style.css"/>
    <link  href="<?php echo base_url();?>assets/ang/css/angular-datepicker.css" rel="stylesheet" type="text/css" />
    <link  href="<?php echo base_url();?>assets/ang/css/selectize.css" rel="stylesheet" type="text/css" />
   
    <script src="http://cdnjs.cloudflare.com/ajax/libs/select2/3.4.0/select2.min.js"></script>
	<script src="https://rawgithub.com/angular-ui/ui-select2/master/src/select2.js"></script>
	
    <link rel="stylesheet" href="<?php echo base_url();?>assets/ang/uiselect/select.css">
   <script type="text/javascript" src="<?php echo base_url();?>assets/qz/dependencies/rsvp-3.1.0.min.js"></script>
   <script type="text/javascript" src="<?php echo base_url();?>assets/qz/dependencies/sha-256.min.js"></script>
   <script type="text/javascript" src="<?php echo base_url();?>assets/qz/qz-tray.js"></script>
   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/3.4.5/select2.css"/>    
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.8.5/css/selectize.default.css"/>
   
</head>
<body data-ng-app="myApp" data-ng-controller="returnController">
	<script type="text/javascript" src="<?php echo base_url();?>assets/ang/js/angular-datepicker.js"></script>
   	<?php $this->load->view('templates/header');?>
    
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-primary">
                	<div class="panel-body">
                	 	<div class="row">
                			<div class="col-md-2">
                				<label>Invoice No:</label>
                			</div>
                			<div class="col-md-6">
								<input class="form-control" data-ng-model="tmpCode" placeholder="Enter invoice no.."  type="text"/>
                			</div>
                			<div class="col-md-2">
                				<button ng-click="getInvoice();" class="btn btn-default"> GO </button>
                			</div>
                		</div>
                	
                	</div>
                	
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-4"><span class="panel-title">Order Summary</span></div>
                            <div class="col-md-4"><span>Today is: {{getDate()}}</span></div>
                            <div class="col-md-3 col-md-push-1"><span>Total Orders - </span><span class="badge">{{totOrders}}</span></div>
                        </div>
                        
                    </div>
                    <div class="panel-body" style="">
                        
                        <ul class="list-group">
                        	<li  class="list-group-item">
                        		 <div class="row">
                                    <div class="col-md-1">
                                    	SL
                                    </div>
                                    <div class="col-md-4">
                                        Name
                                    </div>
                                    <div class="col-md-2">
                                        Rate
                                    </div>
                                    <div class="col-md-1">
                                       Quantity
                                    </div>
                                    <div class="col-md-1">
                                        Amount
                                    </div>
                                </div>
                        	</li>
                            <li class="list-group-item" data-ng-repeat="item in order">
                                <div class="row">
                                    <div class="col-md-1">
                                    	{{$index + 1}}
                                    </div>
                                    <div class="col-md-4">
                                        {{item.itemName}}
                                    </div>
                                    <div class="col-md-2">
                                        {{item.purchasePrice}}
                                    </div>
                                    <div class="col-md-1">
                                       {{item.qty}}
                                    </div>
                                    <div class="col-md-1">
                                        <div class="label label-success">{{item.purchasePrice * item.qty | currency:"":0}}</div>
                                    </div>
                                    
                                
                                </div>
                            </li>
                        </ul>
                        <div class="text-warning" data-ng-hide="order.length">
                            Nothing ordered yet!
                        </div>
                    </div>
                    
                </div>
            </div>
            
            <div class="col-md-4">
            	<div class="panel-footer" >
                     <div class="row top-buffer">
                     	<div class="col-md-4">Date: </div>
                     	<div class="col-md-4">
	                     	<datepicker date-format="yyyy-MM-dd">
								<input class="form-control" ng-model="tdate" type="text"/>
							</datepicker>
						</div>
                     </div>
                     
                     <div class="row  top-buffer">
                        	<div class="col-md-4"><span class="panel-title">Supplier</span></div>
                        	
                        	<div class="col-md-8">
                        		<select style="width: 100%" ui-select2 ng-model="customer">
							        <option ng-repeat="cust in customers" value="{{cust.componentId}}">{{cust.name}}</option>
							    </select>
                			</div>
                		</div>
	                	
                </div>
            	<div class="panel-body" data-ng-show="order.length">
                	<div class="row"> 
                        <h3><span class="label label-primary">Total: {{getTotal() | currency:"TAKA ":0}}</span></h3>
               		</div>
               		<!-- 
               		<div class="row"> 
               			<div class="col-md-4"><span class="panel-title">Commission (%):</span></div>
                        <div class="col-md-4"> <input class="form-control" data-ng-model="discount"/> </div>
               		</div>
               		<div class="row"> 
               			<div class="col-md-4"><span class="panel-title"><b>Net amount:</b></span></div>
                        <div class="col-md-4"> <b>{{netAmount() | currency:" ":0}}</b></div>
               		</div>
             		<div class="row top-buffer"> 
            		 	<div class="col-md-4"><b>Paid:</b> </div>
            			<div class="col-md-4"><b>{{paidAmount()  | currency:" ":0}}</b></div>
            			<div class="col-md-4"><input type="checkbox" class="form-control" data-ng-model="showAll"/>Show All</div>
            		</div>
            		<div class="row top-buffer" data-ng-repeat="account in accounts" data-ng-show="($index<1 && showAll==false) || ($index<10 && showAll==true) ">
            			<div class="col-md-4">{{account.uniqueCode}}: </div>
            			<div class="col-md-4"><input class="form-control" data-ng-model="account.amount"/></div>
            		</div>
            	-->
            	</div>
               <div class="panel-footer" data-ng-show="order.length">
                 	<div>
                  		<span class="btn btn-default btn-marginTop" data-ng-click="clearOrder()" data-ng-disabled="!order.length">Clear</span>
                  		<span class="btn btn-danger btn-marginTop" data-ng-click="doReturn();" data-ng-disabled="!order.length">Return</span>
                  		
                  	</div>
               	</div>
            	
            </div>
            
            <div id="printDiv" data-ng-show="false">
            	<table width="95%" style="font-size:10px">
            		<thead>
            			<tr>
            				<td colspan="5" style="text-align:center;border-bottom: 1px dotted black;">
            					{{receipt.line1}}<br/>
            					{{receipt.line2}}<br/>
            					{{receipt.line3}}<br/>
            					{{receipt.line4}}<br/>
            					{{receipt.line5}}<br/>
            					
            				</td>
            			</tr>
            			
            			<tr>
            				<td colspan="5" style="text-align:center;border-bottom:1px dotted black;">
            					Invoice No: {{invoiceNo}} |
            					Date: {{getDate() | date:'dd-MMM-yyyy'}}
            				</td>
            			</tr>
            			
            			<th width="5%">SL</th><th width="40%">Item</th><th width="15%">Rate</th><th width="5%">Qty</th><th width="30%">Price</th>
            		</thead>
            		<tbody>
            			<tr data-ng-repeat="item in order">
            				<td>{{$index + 1}}</td><td>{{item.itemName}}</td><td style="text-align:center">{{item.salePrice}}</td><td style="text-align:center">{{item.qty}}</td><td style="text-align:center">{{item.qty*item.salePrice}}</td>
            			</tr>
            		</tbody>
            		<tfoot>
            			<tr>
            	<th colspan="5" style="text-align:right;border-top:2px dotted black;padding-right:20px"> Amount - {{getTotal()}}</th>
            			</tr>
						
            			<tr style="text-align:center;">
            				<td colspan="5" style="padding-top:30px">
            					=Thank you for shopping at {{receipt.line1}}=
            				</td>
            			</tr>
            			<tr>
            				<td colspan="5" style="text-align:center;border-top:1px dotted black;">
            					PROMO ITEMS ARE NOT REFUNDABLE<br/>
            					MUST PRODUCE THIS RECEIPT FOR ANY CHANGE
            				</td>
            			</tr>
            			<tr>
            				<td colspan="5" style="text-align:center;border-top:1px dotted black;">
            					<small>Powered by NetSoft Ltd, 01824412272</small>
            				</td>
            			</tr>
            		</tfoot>
            	</table>
            </div>
 
 	   <!--  
            <div class="col-md-4">
                <div class="panel panel-primary">
                    <div class="panel-body">
                        <ul id="myTab" class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#drink" role="tab" data-toggle="tab">Drinks</a></li>
                            <li role="presentation"><a href="#food" role="tab" data-toggle="tab">Food</a></li>
                            <li role="presentation"><a href="#new" role="tab" data-toggle="tab">New Item</a></li>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="drink">
                                <button class="btn btn-primary btn-pos btn-marginTop" data-ng-repeat="item in drinks" data-ng-bind="item.name" data-ng-click="addToOrder(item,1)"></button>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="food">
                                <button class="btn btn-warning btn-pos btn-marginTop" data-ng-repeat="item in foods" data-ng-bind="item.name" data-ng-click="addToOrder(item,1)"></button>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="new">
                                <br />
                                <form class="form" name="formCreate" novalidate>
                                    <div class="col-md-4">
                                        <label>Name*</label>
                                        <input type="text" class="form-control" data-ng-model="new.name" required />
                                    </div>
                                    <div class="col-md-3">
                                        <label>Price*</label>
                                        <input type="number" class="form-control" data-ng-model="new.price" required />
                                    </div>
                                    <div class="col-md-3">
                                        <label>Category*</label>
                                        <select class="form-control" data-ng-model="new.category" required>
                                            <option>Drinks</option>
                                            <option>Foods</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <br />
                                        <button class="btn btn-default btn-marginTop" data-ng-click="addNewItem(new)" data-ng-disabled="formCreate.$invalid">Add</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer"></div>
                </div>
            </div>
        
       --> 
        
        
        </div>
    </div>
</body>
<script type="text/javascript">

	var cfg = null;
	
	function printDiv(divid){
		var printData = [
			{
			    type: 'html',
			    format: 'plain',
			    data: '<html>' + document.getElementById(divid).innerHTML+ '</html>'
			}
			];
		qz.websocket.connect().then(function() { 
			return qz.printers.getDefault();
		}).then(function(printer) {
	  		if (cfg == null) {
	            cfg = qz.configs.create(printer);
	        }
	  		return qz.print(cfg, printData);
		}).catch(function(e) { console.error(e); });
	}


    $(document).ready(function () {
        $("#myTab a").click(function (e) {
            e.preventDefault();
            $(this).tab('show');
        });

        deployQZ();

        
    });
</script>
</html>


