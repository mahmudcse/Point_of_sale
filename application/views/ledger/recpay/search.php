<?php
/*
 * This is generic search page. It uses '$searchAction', '$addmodifyAction', $searchDisplayTxt and $propertyArr
 * it calls getObjectPropertyValue($obj, $property) to get the display text from the object It will be varied based on various search page
 * 
 * For custom search we need to implement by own
 * 
 * Created on
 * Created by 
 */
?>
 <h2 style='text-align: center' class="pull-right"><?php echo $page_title; ?></h2>
<script type="text/javascript">
	function showLedger(accountId, userId){
		form = document.getElementById('showLedgerForm');
		form.accountId.value = accountId;
		form.userId.value = userId;
		form.submit();
	}
	
	 
</script>

<div id="content">
<?php echo form_open($ledgerUrl,['id' => 'showLedgerForm']);?>
	<?php echo form_hidden('accountId',0);?>
	<?php echo form_hidden('userId',0);?>
	<?php echo form_hidden('stdate',$stdate);?>
	<?php echo form_hidden('etdate',$etdate);?>
<?php echo form_close();?>
<?php echo form_open($searchAction);?>

	<table style="width:80%;margin: auto;">
			<tr>	
				<td>From : <br/> <?php echo form_input('stdate',$stdate,"class=\"dtpkr\"");?> </td>
				<td>To: <br/><?php echo form_input('etdate',$etdate, "class=\"dtpkr\"");?></td>
				<td>Customer Group: <br/><?php echo form_dropdown('custgroup',$custgroups, $custgroup);?></td>
				
				<td><input type="submit" id="addProduct" value="Show"/></td>
			</tr>
			
	</table>	
<?php echo form_close();?>
</div>
<div class="box">
	<div class="box-header">
		<!-- CONTROL TABS START -->
		<ul class="nav nav-tabs nav-tabs-left">
			<li class="active">
            	<a href="#list" data-toggle="tab"><i class="icon-align-justify"></i> 
					Receipts &amp; Payments
               	</a>
           	</li>
		</ul>
    	<!-- CONTROL TABS END -->
	</div>		
	<div class="box-content padded">
		<div class="tab-content">            
	            <!-- TABLE LISTING STARTS -->
	   		<div class="tab-pane box active" id="list">
	            <div id="search_result">
	            	<table style="width: 100%;">
	            		
	            			
						<tr>
							<td valign="top">
								<!-- RECEIVABLES -->
								<?php $this->load->view('ledger/recpay/rec.php');?> 
							</td>
								
							<td valign="top">
								<!-- PAYABLES -->
								<?php $this->load->view('ledger/recpay/pay.php');?> 
								
							</td>
							
						</tr>
						
						<tr>
	            			<td align="left">
	            				<input type="button" onclick="printIt('rec');" value=" Print " style="padding:10px"/>
	            			</td>
	            			<td align="left">
	            				<input type="button" onclick="printIt('pay');" value=" Print " style="padding:10px"/>
	            			</td>
	            		</tr>
	            		
					</table>
				</div>
			</div>
            <!-- TABLE LISTING ENDS -->
		</div>
	</div>
</div>
