<style type="text/css">
				
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
	</style>
<div class="box-content padded">
		<div class="tab-content">            
	            <!----TABLE LISTING STARTS--->
	   		<div class="tab-pane box active" id="list">
	            <div id="search_result">
	            	
	            	<table id="rpt" style="width: 50%;margin: auto;min-width: 500px;">

	            		<tr>
	            			<td colspan="2"> 
	            				<h1>Profit and Loss Statement</h1>
	            				Date: <?php echo $stdate.' to '.$etdate;?>
	            				<br/><br/>
	            			</td>
	            		</tr>
						
						<tr>

	            			<th style="text-align: left;">Revenue</th>

	            			<th style="text-align: right;"><?php echo $revenue;?></th>

	            		</tr>

	            		<tr>

	            			<th style="text-align: left;padding-left: 20px;"><span onclick="toogle('sales');">&#9658;</span> Revenue From Sales</th>

	            			<th style="text-align: right;padding-right: 20px;"><?php echo $sales;?></th>

	            		</tr>



	            		<?php foreach ($salesdata as $row):



	            		if($row->sales > 0){

	            		?>

	            		<tr class="sales">

	            			<td style="text-align: left;padding-left: 40px;"><?php echo $row->itemName;?></td>

	            			<td style="text-align: right;padding-right: 40px;"><?php echo $row->sales;?></td>

	            		</tr>

	            		<?php } endforeach;?>

	            		<tr >

	            			<th style="text-align: left;padding-left: 20px;"> <span onclick="toogle('commission');">&#9658;</span>  Revenue From Commission</th>

	            			<th style="text-align: right;padding-right: 20px;"><?php echo $commission;?></th>

	            		</tr>

	            		<?php foreach ($salesdata as $row):

	            		if($row->commission > 0){

	            		?>

	            		<tr class="commission">

	            			<td style="text-align: left;padding-left: 40px;"><?php echo $row->itemName;?></td>

	            			<td style="text-align: right;padding-right: 40px;"><?php echo $row->commission;?></td>

	            		</tr>

	            		<?php } endforeach;?>

	            		<tr >

	            			<th style="text-align: left;"> <span onclick="toogle('cogs');">&#9658;</span>  COGS</th>

	            			<th style="text-align: right;"><?php echo $cogs;?></th>

	            		</tr>

	            		<?php foreach ($salesdata as $row):

	            		if($row->cogs > 0){

	            		?>

	            		<tr class="cogs">

	            			<td style="text-align: left;padding-left: 40px;"><?php echo $row->itemName;?></td>

	            			<td style="text-align: right;padding-right: 40px;"><?php echo $row->cogs;?></td>

	            		</tr>

	            		<?php } endforeach;?>



	           			<tr >

			   	        	<th style="text-align: left;">  <span onclick="toogle('opexp');">&#9658;</span>  Other Operating Expense</th>

			   	            <th style="text-align: right;"><?php echo $opexp;?></th>

			   	        </tr>

			   	           <?php foreach ($opexpdata as $row):

			   	           if($row->opexp > 0){

			   	          ?>

			   	           <tr class="opexp">

			   	           	<td style="text-align: left;padding-left: 40px;"><?php echo $row->accountName;?></td>

			   	            <td style="text-align: right;padding-right: 40px;"><?php echo $row->opexp;?></td>

			   	        </tr>

	            		<?php } endforeach;?>











	            		<tr >

	            			<th style="text-align: left;">Gross Profit ( Revenue - COGS - Operating Expense )</th>

	            			<th style="text-align: right;"><?php echo $grossProfit;?></th>

	            		</tr>

	            		<tr >

	            			<th style="text-align: left;padding-left: 20px;">  <span onclick="toogle('exp');">&#9658;</span> Other Expense</th>

	            			<th style="text-align: right;padding-right: 20px;"><?php echo $otherexp;?></th>

	            		<?php foreach ($expdata as $row):

	            		if($row->exp > 0){

	            		?>

	            		<tr class="exp">

	            			<td style="text-align: left;padding-left: 40px;"><?php echo $row->accountName;?></td>

	            			<td style="text-align: right;padding-right: 40px;"><?php echo $row->exp;?></td>

	            		</tr>

	            		<?php } endforeach;?>

	            		<tr >

	            			<th style="text-align: left;">Net Profit ( Gross Profit - Other Expense)</th>

	            			<th style="text-align: right; font-size:22px; font-weight:bold"><?php echo $netProfit;?></th>

	            		</tr>

					</table>


				</div>
			</div>
            <!----TABLE LISTING ENDS--->
		</div>
	</div>