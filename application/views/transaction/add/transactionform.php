<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

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
		if($("#ttldr").text() != $("#ttlcr").text())
		{
			alert("Total debit and credit amounts must be same");
			return;
		}
		if($("#ttldr").text() == "0")
		{
			alert("Noting to save");
			return;
		}
	}else  if('save' == action.toLowerCase()){
		formObj.action = '<?php echo base_url('index.php/'.$component.'/save');?>';
		if($("#ttldr").text() != $("#ttlcr").text())
		{
			alert("Total debit and credit amounts must be same");
			return;
		}
		if($("#ttldr").text() == "0")
		{
			alert("Noting to save");
			return;
		}
	}else  if('cancel' == action.toLowerCase()){
		formObj.action = '<?php echo base_url('index.php/'.$component.'/search');?>';
	}else{
		alert('Invalid action');
		return;
	}
	formObj.submit();
}
</script>
<style>
<!--
input{width: 100px;}
-->
</style>
<div id="userinput">
	<?php echo form_open($component.'/save');?>
		<?php foreach ($inputs as $inp):?>
			<?php if(isset($inp['type']) && $inp['type'] == 'hidden'){echo form_hidden( $inp['fielddata']['name'],$inp['fielddata']['value']);}?>
		<?php endforeach;?>
		<?php //$this->load->view('templates/buttonbar.php');?>
		<br/>
		<table>
			<tr>
				<th>Particulars</th>
				<td colspan="2"><?php echo form_textarea($inputs['description']['fielddata']);?></td>
				<th>Reference</th>
				<td><?php echo form_input($inputs['name']['fielddata']);?></td>
				<th>Date</th>
				<td><?php echo form_input($inputs['tdate']['fielddata']);?></td>
			</tr>
		</table>
		
		<br/> 
		
	
	<table id="tdr" style="background-color:#ccffcc;width:45%; float:left;">
		<tr><th colspan="6" style="border-bottom:1px solid #AAAAAA;">Debits</th></tr>
		<tr><th>User</th><th>Account</th><th>Item</th><th>Unit Price</th> <th>Quantity</th><th>Amount</th></tr>
		<tr><th> <input type="button"  onclick="add(1);" style="padding: 3px 8px;font-weight: bold;font-size: large;" value=" + "/> </th><th colspan="3">Total</th><th id="ttldr">0.00</th></tr>
	</table>
	<table id="tcr" style="background-color:#ffe6e6;width:45%;">
		<tr><th colspan="6" style="border-bottom:1px solid #AAAAAA;">Credits</th></tr>
		<tr><th>User</th><th>Account</th><th>Item</th><th>Unit Price</th> <th>Quantity</th><th>Amount</th></tr>
		<tr><th> <input onclick="add(-1);" type="button" style="padding: 3px 8px;font-weight: bold;font-size: large;" value=" + "/> </th><th colspan="3">Total</th><th id="ttlcr">0.00</th></tr>
	</table>
	<input type="hidden" name="rowcount" id="rowcount"/>
	<div style="clear: both;"></div>
	<?php $this->load->view('templates/buttonbar.php');?>

	<?php echo form_close();?>
	<br />
	<hr />
</div>

<script type="text/javascript">
	data = <?php echo $detailData;?>;
	itemdata = <?php echo $itemjson;?>;
	accountdata = <?php echo $accountjson;?>;
	userdata = <?php echo $userjson;?>;

	function getItem(id){
		for(item of itemdata){
			if(item.componentId == id)
				return item.uniqueCode;
		}

		return '';
	}
	function getAccount(id){
		
		for(account of accountdata){
			if(account.componentId == id)
				return account.uniqueCode;
		}
		return '';
	}
	function getUser(id){
		for(user of userdata){
			if(user.componentId == id){
				return user.name;
			}
		}
		return '';
	}
	function add(type){
		data.push({'detailId':'','userId':'','itemId':'<?php echo Applicationconst::ITEM_CASH;?>','accountId':'','type':type,'quantity':0,'unitPrice':1});
		render();
	}
	function render(){
		$('.dynarow').remove();
		drAmt = 0.0;
		crAmt = 0.0;
		
		for(i=0;i<data.length;i++){
			tr = "<tr class=\"dynarow\">";
			tr += "<td><input type=\"hidden\" name=\"userId" + i + "\" id=\"userId" + i + "\" value=\""+data[i].userId+"\"/>  <input type=\"text\" name=\"user" + i + "\" id=\"user" + i + "\" value=\""+getUser(data[i].userId)+"\"/></td>";
			tr += "<td><input type=\"hidden\" name=\"accountId" + i + "\"  id=\"accountId" + i + "\" value=\"" + data[i].accountId + "\"/> <input type=\"text\" name=\"account" + i + "\"  id=\"account" + i + "\" value=\""+getAccount(data[i].accountId)+"\"/></td>";
			tr += "<td><input type=\"hidden\" name=\"itemId" + i + "\" id=\"itemId" + i + "\" value=\"" + data[i].itemId + "\"/> <input type=\"text\" name=\"item" + i + "\" id=\"item" + i + "\" value=\""+getItem(data[i].itemId)+"\"/></td>";
			tr += "<td><input type=\"hidden\" name=\"type" + i + "\" id=\"type" + i + "\" value=\""+data[i].type+"\"/>    <input type=\"text\" onchange=\"updateUnitPrice(" + i + ",this.value);\" name=\"unitPrice" + i + "\" value = \"" + data[i].unitPrice + "\" /></td>";
			tr += "<td><input type=\"text\" onchange=\"updateQuantity(" + i + ",this.value);\" name=\"quantity" + i + "\" value = \"" + data[i].quantity + "\" /></td>";
			tr += "<td>" + (data[i].unitPrice*data[i].quantity) + "</td>";
			tr += "<td> <input type=\"button\" style=\"padding:3px;\" value=\" - \" onclick=\"deleterow(" + i + ");\"/> </td>";
			tr += "</tr>"; 
			if(data[i].type == 1){
				drAmt -= -1*data[i].unitPrice*data[i].quantity;
				$('#tdr tr:last').before(tr);
			}else if(data[i].type == -1){
				crAmt -= -1*data[i].unitPrice*data[i].quantity;
				$('#tcr tr:last').before(tr);
			}
		}

		$("#ttldr").text(drAmt.toFixed(2));
		$("#ttlcr").text(crAmt.toFixed(2));
		$("#rowcount").val(data.length);
	}

	$(function() {
	    var usertemp = <?php echo $userjson;?>;
	    udata = $.map(usertemp, function (item) {
            return {label: item.name, id: item.componentId};
        });
	    var acctemp = <?php echo $accountjson;?>;
	    adata = $.map(acctemp, function (item) {
            return {label: item.uniqueCode, id: item.componentId};
        });
	    var itmtemp = <?php echo $itemjson;?>;
	    idata = $.map(itmtemp, function (item) {
            return {label: item.itemName, id: item.componentId};
        });
        
	    $(document).on('keydown.autocomplete', "input[id^=user]", function() {
	        $(this).autocomplete({
	        		source: udata,
	    			select: function( event, ui ) {
	    				indx = this.id.replace("user","");
	    				data[indx].userId = ui.item.id;
	    			},
	    			change: function (event, ui) { render(); }
	        });
	    });
	    
	  
	    $(document).on('keydown.autocomplete', "input[id^=account]", function() {
	 		$(this).autocomplete({
		      source: adata,
		      select: function( event, ui ) {
			      indx = this.id.replace("account","");
			      data[indx].accountId = ui.item.id;
  				},
  				change: function (event, ui) { render(); }
		       
	 		});
	    });
	    $(document).on('keydown.autocomplete', "input[id^=item]", function() {
	    	$(this).autocomplete({
		      source: idata,
		      select: function( event, ui ) {
			      indx = this.id.replace("item","");
			      data[indx].itemId = ui.item.id;
				},
				change: function (event, ui) { render(); }
		    });
	    });
	  });
	  
	function updateUnitPrice(indx, val){
		data[indx].unitPrice = val;
		render(); 
	};
	function updateQuantity(indx, val){
		data[indx].quantity = val;
		render(); 
	};
	function deleterow(indx){
		data.splice(indx, 1);
		render(); 
	}
	render();

	$('#findProduct').keypress(function(){
		var outerHeight = $('#findProduct').outerHeight(true);
		var offsets = $('#findProduct').offset();
		var divheight = offsets.top;
		var margintop = outerHeight + divheight + 'px';
		$('.ui-autocomplete').css("margin-top", margintop);

		var outerWidth = $('#findProduct').outerWidth(true) + 100 + 'px';
		$('.ui-autocomplete').css("margin-left", outerWidth);
	});
	
</script>
