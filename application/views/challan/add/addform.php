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
<div id="userinput">
	<?php echo form_open($component.'/save');?>
		<?php foreach ($inputs as $inp):?>
			<?php if($inp['type'] == 'hidden'){echo form_hidden( $inp['fielddata']['name'],$inp['fielddata']['value']);}?>
		<?php endforeach;?>
		<?php //$this->load->view('templates/buttonbar.php');?>
		<br/>
		<table>
			
			<?php foreach ($inputs as $inp):?>
			<?php if($inp['type'] != 'hidden'){?>
			<tr>
				<td><label><?php echo isset($inp['label'])?$inp['label']: $inp['fielddata']['name'];?></label> </td>
				<td>
					<?php 
					if($inp['type']=='textfield')
						echo form_input($inp['fielddata']);
					elseif ($inp['type']=='dropdown')
						echo form_dropdown($inp['fielddata']['name'], $inp['fielddata']['options'], $inp['fielddata']['value']);
					elseif ($inp['type']=='textarea')
						echo form_textarea($inp['fielddata']);
					else 
						echo 'Type<>field map does not exist for type '.$inp['type'];
					?>
				</td>
			</tr>
			<?php }?>
			<?php endforeach;?>
			
		</table>

		<br/> 
		<?php $this->load->view('templates/buttonbar.php');?>
		
		<?php echo form_close();?>
		<br/>
		
		
		<table style="width: 100%;border-spacing: 10px;border-collapse: separate;">
			<tr>
				<td id="invoice"  style="padding: 5px;width:50%;border: 0px solid #999999;">
					
					<?php $this->load->view('challan/add/invoice');?>				</td>
				<td id="challan" style="width:50%;padding:5px;border: 0px solid #999999; vertical-align: top;">
				
					<?php $this->load->view('challan/add/challan');?>				</td>
			</tr>
			<tr>
				<td><input name="button" type="button" onclick="printDiv('invoice');" value="Print Invoice" style="padding:10px"/>
				  <input type="button" onclick="document.location='../invoicepdf/<?php echo $id;?>'" value="Download PDF" style="padding:10px"/>
					<input type="button" onclick="document.location='../invoiceemail/<?php echo $id;?>'" value="Email" style="padding:10px"/>
				 </td>
				<td>
					<input type="button" onclick="printDiv('challan');" value="Print Challan" style="padding:10px"/>
					<input type="button" onclick="document.location='../challanpdf/<?php echo $id;?>'" value="Download PDF" style="padding:10px"/>
					<input type="button" onclick="document.location='../challanemail/<?php echo $id;?>'" value="Email" style="padding:10px"/>
					
				</td>
			</tr>
		</table>

	
	<br />
	<hr />
	 <iframe name="print_frame" width="0" height="0" frameborder="0" src="about:blank"></iframe>
	<script type="text/javascript">
            printDivCSS = new String ('<link href="myprintstyle.css" rel="stylesheet" type="text/css">')
            function printDiv(divId) {
                window.frames["print_frame"].document.body.innerHTML=printDivCSS + document.getElementById(divId).innerHTML;
                window.frames["print_frame"].window.focus();
                window.frames["print_frame"].window.print();
            }
 	</script>
</div>