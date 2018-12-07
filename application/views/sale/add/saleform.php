<script type="text/javascript">
function prepareAction(formObj, action){
	if(formObj==null){
		alert('Undefined form');
		return;
	}
	if('delete' == action.toLowerCase()){
		if( confirm("Are you sure?")){
			formObj.action = '<?php echo base_url('index.php/'.$component.'/delete');?>';
		}else{
			return;
		}
	}else if('update' == action.toLowerCase()){
		formObj.action = '<?php echo base_url('index.php/'.$component.'/save');?>';
	}else  if('save' == action.toLowerCase()){
		formObj.action = '<?php echo base_url('index.php/'.$component.'/save');?>';
	}else  if('cancel' == action.toLowerCase()){
		formObj.action = '<?php echo base_url('index.php/'.$component.'/search');?>';
	}else{
		alert('Invalid action');
		return;
	}
	formObj.submit();
}
 </script>
<style type="text/css">
<!--
.overlay
 {
     position: absolute;
     top: 0;
     left: 0;
     right: 0;
     bottom: 0;
     background-color: rgba(0, 0, 0, 0.85);
     z-index: 9999;
     display: inline-block;
 }
</style>
<div id="userinput">
	<?php echo form_open($component.'/save');?>
	<?php
		foreach ($inputs as $inp):?>
			<?php if(isset($inp['type']) && $inp['type'] == 'hidden'){echo form_hidden( $inp['fielddata']['name'],$inp['fielddata']['value']);}?>
		<?php endforeach;?> 
	<input type="hidden" name="productCounter" id = "productCounter" value="0"/>
	<div class="container">
		<div class="overlay" style="display: none;padding: 100px 200px;">
		
			<div class="row" style="background-color: white;padding: 25px 25px 25px 0px;">
				<div class="col-md-3 right">
					<label>Due Amount:&nbsp;</label><label id="dueAmt"> </label>
				</div>
				<div class="col-md-2 right">
					<label>No. of Installments:</label>
				</div>
				<div class="col-md-2">
					<input class="form-control" value="" name="installmentNo" id="installmentNo" />
				</div>
				<div class="col-md-2 right">
					<label>Period (months):</label>
				</div>
				<div class="col-md-2">
					<input class="form-control" value="" name="installmentPeriod" id="installmentPeriod" onchange="renderInstallmentRows();"/>
				</div>
			</div>
			<div class="row" id="insRow" style="background-color: white;padding: 0px 25px 0px 25px;">
				<div class="col-md-12" id="instDiv">
					

				</div>
			</div>
			<div class="row" style="background-color: white;padding: 0px 25px 25px 25px;">
				<div class="col-md-12">
					<button type="button" class="btn btn-default" id="completeInstallments" disabled="true"> OK </button>
					<button type="button" class="btn btn-default" id="cancel"> Cancel </button>
					<!-- <button type="button" class="btn btn-default" onclick="$('.overlay').toggle();" id="cancel"> Cancel </button> -->
				</div>
			</div>
		 	

	</div>

		<div class="row">
			<div class="col-md-4 right"><label>Add Item</label></div>
			<div class="col-md-4">
				<input name="findProduct" placeholder="Type product here" id="findProduct" class="item form-control" style="text-align: left;"/>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<?php foreach ($hotItem as $item):?>
				<div class="col-md-1 full-border" style="height:130px; overflow: hidden;margin: 1px;cursor: pointer ;" onclick="addItem('<?php echo $item->componentId;?>', '<?php echo htmlentities ($item->itemName);?>','<?php echo htmlentities ($item->category2);?>','<?php echo htmlentities ($item->category3);?>', '<?php echo $item->salePrice;?>', '<?php echo $item->unit;?>','');">
					<img  alt="" src="<?php echo base_url('assets/images/default.png')?>" style="width:100px;"/>
					<div style="margin-top: -25px;" title="<?php echo $item->uniqueCode;?>">
					<?php echo $item->itemName;?><br/><!--  
					<?php echo $item->category3;?><br/>
					<?php echo $item->category2;?><br/>-->
					TK: <?php echo $item->salePrice;?><br/> 
					</div> 
				</div>
			<?php endforeach;?>
		</div>
	</div>
	<input type="hidden" name="totaldr" value="<?php echo count($account);?>">
	<div class="container">
		<div class="row top-buffer">
			<div class="col-md-8" id="productDiv">
				
				<div class="row bottom-border" style="background-color:silver;">
					<div class="col-md-3"><label> <?php echo get_phrase('Product');?> </label></div>
					<div class="col-md-2"><label> <?php echo get_phrase('Unit Price');?></label> </div>
					<div class="col-md-2"><label> <?php echo get_phrase('Quantity');?> </label></div>
					<div class="col-md-2">&nbsp;</div>
					<div class="col-md-2"><label> <?php echo get_phrase('Description');?></label> </div>
					<div class="col-md-2">&nbsp;</div>
				</div>
			</div>
			<div class="col-md-4">
				
				<div class="row">
					<div class="col-md-4 right">
						<Label>Date </Label>
					</div>
					<div class="col-md-6">
						<?php $inputs['tdate']['fielddata']['class'] = "form-control dtpkr";?>
						<?php echo form_input($inputs['tdate']['fielddata']);?>
					</div>
				</div>
				
				<div class="row top-buffer-narrow">
					<div class="col-md-4 right">
						<Label>Customer </Label>
					</div>
					<div class="col-md-6">
						<?php  echo form_dropdown('userId', $customer, $userId, "class=\"form-control\"");?>
					</div>
				</div>
				
				<div class="row top-buffer-narrow">
					<div class="col-md-4 right">
						<Label>Amount </Label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control" id="amount" readonly="readonly" name="amount" value="1" />
					</div>
				</div>
				
				<div class="row top-buffer-narrow">
					<div class="col-md-4 right">
						<Label>Discount </Label>
					</div>
					<div class="col-md-4">
						<div class="input-group">
							<input type="text" class="form-control" id="percentage" name="percentage" onblur="getamount();"  />
							<Label class="input-group-addon">%</Label>
						</div>
					</div>
					<div class="col-md-4">
						<div class="input-group">
							<input type="text" class="form-control" id="discount" name="discount" value="0.00" onblur="getpercentage()" />
							<Label class="input-group-addon">TK.</Label>
						</div>
					</div>
				</div>
				<?php
					$crCount = 0;

					foreach ( $account as $acId => $acName ) :
					$crCount ++;
				?>
				<div class="row top-buffer-narrow">
					<div class="col-md-4 right">
						<Label><?php echo $acName;?></Label>
					</div>
					<?php if($acId == Applicationconst::ACCOUNT_HEAD_RECEIVABLE){
					?>
					<div class="col-md-4">
						<input type="hidden" readonly="readonly" name="accountIdc<?php echo $crCount;?>" id="accountIdc<?php echo $crCount;?>" value="<?php echo $acId?>" />
						<input type="text" id="acrcvbl" class="form-control" name="quantityc<?php echo $crCount;?>" onchange="renderItem();" id="quantityc<?php echo $crCount;?>" value=""  />
					</div>
					<div class="col-md-4">
						<!-- <button type="button" onclick="calcInstallments();" class="btn btn-default">Installments</button> -->
						<button type="button" id="installment" class="btn btn-default">Installments</button>
					</div>
					<?php 
					}else{?>
					<div class="col-md-6">
						<input type="hidden" readonly="readonly" name="accountIdc<?php echo $crCount;?>" id="accountIdc<?php echo $crCount;?>" value="<?php echo $acId?>" />
						<input type="text" class="form-control" name="quantityc<?php echo $crCount;?>" onchange="renderItem();" id="quantityc<?php echo $crCount;?>" value=""  />
					</div>
					<?php }?>
				</div>
				<?php endforeach;?>
				
				<div class="row top-buffer-narrow">
					<div class="col-md-4 right">
						<Label>Warehouse</Label>
					</div>
					<div class="col-md-6">
						<select name="warehouseId" class="form-control">
					     	<?php foreach ($warehouses as $warehouse) { ?>
					        	<option value="<?php echo $warehouse->componentId;?>"><?php echo $warehouse->name;?></option>
					      	<?php } ?>
				        </select>
					</div>
				</div>
				
				<div class="row top-buffer-narrow">
					<div class="col-md-4 right">
						<Label>Commnets </Label>
					</div>
					<div class="col-md-6">
						<?php
						$inputs['description']['fielddata']['class'] = 'form-control';
						echo form_textarea($inputs['description']['fielddata']);?>
					</div>
				</div>
				
				<div class="row top-buffer-narrow">
					<div class="col-md-4 right">
						<Label>Reference </Label>
					</div>
					<div class="col-md-6">
						<?php
						$inputs['name']['fielddata']['class'] = 'form-control';
						echo form_input($inputs['name']['fielddata']);?>
					</div>
				</div>
				
			</div>
		</div>
		<div class="row top-border">
			<div class="col-md-12">&nbsp;</div>
		</div>
	</div>
				
	<div> 
		<?php $this->load->view('templates/buttonbar.php');?>
	</div>
	
	<br />
	<hr />

</div>


<?php echo form_close();?>

<script type="text/javascript">
var tmpdata = <?php echo $jsonitem;?>;


data = $.map(tmpdata, function (item) {
        return {
            	label: item.itemName, 
            	category: item.category2+' '+item.category3,
            	value: item.componentId,
            	salePrice: item.saleprice,
            	description: item.description,
            	quantity:1,
            	unit:item.unit==null?'':item.unit
        };
    });
var options = {
	    source: data,
	    minLength: 2,
	    select: function( event, ui ) {

		    addItem(ui.item.value, ui.item.label, ui.item.desc, ui.item.desc, ui.item.salePrice, ui.item.unit, '');
		    $(this).val('');

		    return false;
	    }
	};

$("#findProduct").autocomplete(options);

var items = [];
	Array.prototype.remove = function(from, to) {
	  var rest = this.slice((to || from) + 1 || this.length);
	  this.length = from < 0 ? this.length + from : from;
	  return this.push.apply(this, rest);
	};
	
	function deleteIt(i){
		items.remove(i);
        renderItem()
	}

	function addIt(item){

		 found = 0;
         for(i=0;i<items.length;i++){
       	  if(items[i].value == item.value){
           	  found = 1;
           	  items[i].quantity = items[i].quantity+1;
       	  }
         }
         if(found==0)
   	  		items.push(item);
         
         
         renderItem();
	}
	
	function addItem(componentId, itemName,category2,category3, salePrice, unit, description){
		item = {'label': itemName, 
    	'category': category2+' '+category3,
    	'value': componentId,
    	'salePrice': salePrice,
    	'description': description,
    	'quantity':1,
    	'unit':unit};
           
		addIt(item);
	}
	function addQty(i,q){
		items[i].quantity = items[i].quantity+q;
		if(items[i].quantity ==0)
			deleteIt(i);
		else	
			renderItem();
	}

	crSize = <?php echo count($account);?>;
	receivable = <?php echo Applicationconst::ACCOUNT_HEAD_RECEIVABLE;?>;
	
	function renderItem(){
		$('.dynarow').remove();
		amt = 0.0;
		$("#productCounter").val(items.length);	
		for(i=0;i<items.length;i++){
			item = items[i];
			hdns="";
			amt += item.quantity * item.salePrice;
			
			hdns += "<input type=\"hidden\" name=\"itemId"+i+"\" value=\""+item.value+"\"/>";
			qty = "<input type=\"text\" onchange=\"updatequantity(this,"+i+");\" class=\"right  form-control\" name=\"quantity"+i+"\" value=\""+item.quantity+"\"/>";
			price = "<input type=\"text\" onchange=\"updateprice(this,"+i+");\" class=\"right form-control\" name=\"unitPrice"+i+"\" value=\""+item.salePrice+"\"/>";
			del = "<i style=\"cursor:pointer;\" onclick=\"deleteIt("+i+");\" class=\"glyphicon glyphicon-trash\"></i>";
			plus = "<i style=\"cursor:pointer;\" onclick=\"addQty("+i+",1);\" class=\"glyphicon glyphicon-chevron-up\"></i>";
			minus = "<i style=\"cursor:pointer;\" onclick=\"addQty("+i+",-1);\" class=\"glyphicon glyphicon-chevron-down\"></i>";
			desc = "<input type=\"text\"  onchange=\"updatedesc(this,"+i+");\" style=\"text-align:left;\" class=\" form-control\" name=\"description"+i+"\" value=\""+item.description+"\"/>";
			$('#productDiv').append('<div class=\"row top-buffer-narrow dynarow\" ><div class=\"col-md-3\">'+ hdns + item.label + '</div><div class=\"col-md-2\">' + price + '</div><div class=\"col-md-3\"><div class=\"input-group\">'+qty+'<label style="width:50px;" class=\"input-group-addon\">'+item.unit+'</label></div></div> <div class=\"col-md-1\">' +minus+''+plus+ '</div><div class=\"col-md-2\">' + desc + '</div><div class=\"col-md-1\">'+del+'</div></div>');
		}
		$('#amount').val(amt);

		crAmt = 0.0;
		revIdx = -1;
		for(i=1;i<=crSize;i++){
			caccId = $('#accountIdc'+i).val();
			if(caccId!=receivable){
				crAmt -= -$('#quantityc'+i).val();
			}else{
				revIdx = i;
			}
		}

		disc = $('#discount').val();

		$('#acrcvbl').val(amt - crAmt - disc);

	}

	function getamount(){
		per = $('#percentage').val();
		disc = (per*amt)/100;
		disc = disc.toFixed(0);
		$('#quantityc'+revIdx).val(amt - crAmt - disc);
		document.getElementById('discount').value = disc;

		calculateDiscount();
	}
	function getpercentage(){
		disc = $('#discount').val();
		$('#quantityc'+revIdx).val(amt - crAmt - disc);

		percentage = (disc/amt)*100;
		percentage = percentage.toFixed(2);
		document.getElementById('percentage').value = percentage;

		calculateDiscount();
	}

	function updateprice(obj, i){
		
		items[i].salePrice=parseFloat(obj.value);
		renderItem();
	}

	function updatequantity(obj, i){
		items[i].quantity=parseFloat(obj.value);
		renderItem();
	}
	
	function updatedesc(obj, i){
		
		items[i].description=obj.value;
		renderItem();
	}

	function calculateDiscount(){
		renderItem();
	}
	
	$('.blankOnFocus').focus(function() { 
	  $(this).val(''); 
	});

	var instData = [];

	$(document).on('blur', '#installmentNo', function(e){
		e.preventDefault();

		var htm = "<div class=\"row\">"+
		"<div class=\"col-md-4 right\"><label>Date</label></div>"+
		"<div class=\"col-md-4 right\"><label>Amount</label></div>"+
		"<div class=\"col-md-4\">Total: <span id=\"sum_of_installment_amount\"></span></div>"+
		"</div>";
		var instCnt = $('#installmentNo').val();
		var instPrd = $('#installmentPeriod').val();

		for(var i=instData.length;i<instCnt;i++){
			instData.push({dueDate:"2017-10-01",amount:"500"});
		}

		$('#dueAmt').text($('#acrcvbl').val());
		var totalRow='';
		
		for(var i=0;i<instCnt;i++){
			htm += "<div class=\"row top-buffer-narrow\">"+
			"<div class=\"col-md-4 right\"><input type=\"text\" class=\"form-control installment_datepicker\" name=\"installmentDate[]\" value=\"\"/></div>"+
			"<div class=\"col-md-4 right\"><input class=\"form-control installmentAmount\" name=\"insamt[]\" value=\"\" /></div>"+
			"<div class=\"col-md-4\">&nbsp;</div>"+
			"</div>";
		}
		$('#instDiv').html(htm);
	});

	$(document).on('click', '#installment', function(e){
		e.preventDefault();
		$('.overlay').toggle();
	});

	$(document).on('click', '#completeInstallments', function(e){
		$('.overlay').toggle();
	});

	$(document).on('click', '#cancel', function(e){
		e.preventDefault();
		$('#instDiv').html('');
		$('#installmentNo').val('');
		$('#installmentPeriod').val('');
		$('.overlay').toggle();
	});

	$(document).on('blur', '.installmentAmount', function(e){
		e.preventDefault();

		var sum_of_installment_amount = 0;
		var number_of_installment = $('#installmentNo').val();

		for(var i = 0; i < number_of_installment; i++){
			sum_of_installment_amount += Number($('.installmentAmount:eq('+i+')').val());
		}
		$('#sum_of_installment_amount').text(sum_of_installment_amount);

		var due_amount = parseInt($('#dueAmt').html());

		if(due_amount == sum_of_installment_amount){
			$('#completeInstallments').attr('disabled', false);
		}else{
			$('#completeInstallments').attr('disabled', true);
		}
	});

	$(document).on('focus', '.installment_datepicker', function(){
		$('.installment_datepicker').datepicker({
			changeMonth : true,
			changeYear  : true
		});
	});
</script>
