<style type="text/css">
				
            	table#rpt {
 				   border-collapse: collapse;
					}
				table#rpt td { 
				    padding: 0 5px;
				    border-bottom: 1px dotted #999999;
				}
				table#rpt tr:nth-child(2n+1) td {
					background-color:#EEEEEE;
				}
				table#rpt tr:nth-child(2n) td {
					background-color:#EEEEEE;
				}
				table#rpt th { 
				    padding: 0 5px;
				    background-color:#DDDDDD;
				    border-bottom: 1px dotted #999999;
				    border-radius:0;
				}
				table#rpt { 
				    border-spacing: 0;
				    border-collapse: separate;
				}
				
				table.innertbl th{
					border-top: 1px solid silver;
				}
				table.innertbl td{
					padding: 1px;
					font-size: x-small;
					border-bottom: 1px solid silver;
				}
	</style>
<div class="box-content padded">
		<div class="tab-content">            
	            <!--TABLE LISTING STARTS-->
	   		<div class="tab-pane box active" id="list">
	            <div id="search_result">
	            	
	            	<table id="rpt" style="width: 100%;margin: auto;min-width: 500px;">

	            		<tr>
	            			<td colspan="10" align="center"> 
	            				<h1>Sales Report</h1>
	            				Date: <?php echo $stdate.' to '.$etdate;?>
	            				<br/><br/>
	            			</td>
	            		</tr>
						
						<tr bgcolor="#CCCCCC">

							<th style="text-align: left;">Date</th>
							
	            			<th style="text-align: left;">Customer</th>
	            			
	            			<th style="text-align: left;">Invoice</th>
	            			
	            			<th style="text-align: center;">
	            				Particulars<br/>
	            				<table class="innertbl" style="width: 100%;">
	            					<tr>
	            						<th style="width: 60%;">Product</th>
	            						<th style="width: 20%;">Qty</th>
	            						<th style="width: 20%;">Rate</th>
	            					</tr>
	            				</table>
	            			</th>
							<th style="text-align: right;">Quantity</th>
							
	            			<th style="text-align: right;">Amount</th>
	            			
	            			<th style="text-align: right;">Discount</th>
	            			
	            			<th style="text-align: right;">Paid</th>
	            			
	            			<th style="text-align: right;">Due</th>
	            			
	            			<th style="text-align: right;">Actor</th>

	            		</tr>

	            		<?php 
	            		$totalAmount = 0.0;
	            		$totalComission = 0.0;
	            		$totalPaid = 0.0;
	            		$totalDue = 0.0;
	            		$totalQty = 0.0;
	            		 
	            		foreach ($summerydata as $row):
	            		
	            			if (strpos($row->uniqueCode, 'RETURN') !== false) {
	            			
	            				$totalAmount -= $row->totalamount;
	            				$totalComission -= $row->commission;
	            				$totalPaid -= $row->totalpaid;
	            				$totalDue -= $row->totaldue;
	            				$totalQty -= $row->totalQty;
	            			}else{
			            		$totalAmount += $row->totalamount;
			            		$totalComission += $row->commission;
			            		$totalPaid += $row->totalpaid;
			            		$totalDue += $row->totaldue;
			            		$totalQty += $row->totalQty;
	            			}
	            		?>

	            		<tr>
	            			<td style="text-align: left;"><?php echo $row->tdate;?></td>

	            			<td style="text-align: left;"><?php echo $row->customerName;?></td>
	            			
	            			<td style="text-align: left;"><?php echo $row->uniqueCode;?></td>
	            			
	            			<td style="text-align: left;"> 
	            				<table class="innertbl" style="width: 100%;">
	            					<?php foreach ($detail[$row->componentId] as $drow):?>
	            					<tr>
	            						<td style="width: 60%;"><?php echo $drow->itemName;?></td>
	            						<td style="width: 20%;"><?php echo $drow->quantity;?></td>
	            						<td style="width: 20%;"><?php echo $drow->unitPrice;?></td>
	            					</tr>
	            					<?php endforeach;?>
	            				</table>
	            			</td>
	            			
	            			<td style="text-align: right;"><?php echo $row->totalQty;?></td>

	            			<td style="text-align: right;"><?php echo $row->totalamount;?></td>
	            			
	            			<td style="text-align: right;"><?php echo $row->commission;?></td>
	            			
	            			<td style="text-align: right;"><?php echo $row->totalpaid;?></td>
	            			
	            			<td style="text-align: right;"><?php echo $row->totaldue;?></td>
	            			
	            			<td style="text-align: right;"><?php echo $row->salesperson;?></td>

	            		</tr>

	            		<?php  endforeach;?>


	            		<tr>

	            			<th style="text-align: right;" colspan="4">Total </th>
	            			<th style="text-align: right;font-weight:bold"><?php echo $totalQty; ?></th>
							<th style="text-align: right;font-weight:bold"><?php echo $totalAmount; ?></th>
	            			<th style="text-align: right;font-weight:bold"><?php echo $totalComission;?></th>
	            			<th style="text-align: right;font-weight:bold"><?php echo $totalPaid;?></th>
	            			<th style="text-align: right;font-weight:bold"><?php echo $totalDue;?></th>
							<th>&nbsp;</th>
	            		</tr>

					</table>


				</div>
			</div>
            <!----TABLE LISTING ENDS--->
		</div>
	</div>