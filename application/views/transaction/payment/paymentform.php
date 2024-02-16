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
		formObj.action = '<?php echo base_url('index.php/'.$component.'/savePayment');?>';
	}else  if('cancel' == action.toLowerCase()){
		formObj.action = '<?php echo base_url('index.php/'.$component.'/search');?>';
	}else{
		alert('Invalid action');
		return;
	}
	formObj.submit();
}
</script>

<div id="userinput">
	<?php echo form_open($component.'/save');?>
		<?php foreach ($inputs as $inp):?>
			<?php if(isset($inp['type']) && $inp['type'] == 'hidden'){echo form_hidden( $inp['fielddata']['name'],$inp['fielddata']['value']);}?>
		<?php endforeach;?>
	<input type="hidden" name="productCounter" id = "productCounter" value="0"/>
	<table style="width:100%;">

		<tr>
			<td style="border: 1px solid silver;vertical-align: top; width: 70%;">
				<input type="hidden" name="totaldr" id="totaldr" value="<?php echo count($account);?>">
				<table>
					<tr>
						<th  align="right"><div align="right" style="padding-right: 20px">Reference</div></th>
						<td  align="right"><?php echo form_input($inputs['name']['fielddata']);?></td>
					</tr>
					<tr>
						<th align="right"><div align="right" style="padding-right: 20px">Date</div></th>
						<td align="right"><?php echo form_input($inputs['tdate']['fielddata']);?></td>
					</tr>
					<tr>
						<th style="text-align: right;">Pay to </th>
						<td><?php echo form_dropdown($inputs['customerId']['fielddata']['name'], $inputs['customerId']['fielddata']['options'],$inputs['customerId']['fielddata']['value']);?></td>
					</tr>
					
			        <?php
					$crCount = 0;
					foreach ( $account as $acId => $acName ) :
					$crCount ++;
					?>
			  		<tr>
						<th><div align="right" style="padding-right: 20px"><?php echo $acName;?><input type="hidden" readonly="readonly" name="accountIdc<?php echo $crCount;?>" value="<?php echo $acId?>" /> </div></th>
						<td><input type="text" onchange="calcSum();" name="quantityc<?php echo $crCount;?>" id="quantityc<?php echo $crCount;?>" value="0.00" /></td>
					</tr>
					<?php endforeach;?>
			        <tr>
						<th ><div align="right" style="padding-right: 20px">Total Amount</div></th>
						<td><input type="text" class="" id="amount" readonly="readonly" name="amount" value="1" /></td>
					</tr>     
			     	<tr>
						<th style="text-align: right;">Commnets </th>
						<td ><?php echo form_textarea($inputs['description']['fielddata']);?></td>
					</tr>
					
				</table>
			</td>
		</tr>
	</table>
					
	<div align="right"> 
		<?php $this->load->view('templates/buttonbar.php');?>
		</div>
	<?php echo form_close();?>
	<br />
	<hr />

</div>

<script type="text/javascript">
	
	function calcSum(){
		sum = 0.0;
		for(i = 1;i<=$("#totaldr").val();i++){
			sum -= -$("#quantityc"+i).val();
		}
		$("#amount").val(sum);
	}
</script>