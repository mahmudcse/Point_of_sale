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
	            				<h1>Balance Sheet</h1>
	            				Date: <?php echo $stdate.' to '.$etdate;?>
	            				<br/><br/>
	            			</td>
	            		</tr>
	            		<tr>
	            			<th style="text-align: left;">Asset</th>
	            			<th style="text-align: right;"></th>
	            		</tr>
	            		<tr >
	            			<th style="text-align: left;padding-left: 25px;">Current Asset</th>
	            			<th style="text-align: right;padding-right: 25px;"><?php echo $currentAsset;?></th>
	            		</tr>
	            		<?php foreach ($currentAssets as $ca):?>
	            		<tr>
	            			<td style="text-align: left;padding-left: 50px;"><?php echo $ca->name;?></td>
	            			<td style="text-align: right;padding-right: 50px;"><?php echo $ca->amount;?></td>
	            		</tr>
	            		<?php endforeach;?>
	            		<tr >
	            			<th style="text-align: left;padding-left: 25px;">Other Asset</th>
	            			<th style="text-align: right;padding-right: 25px;"><?php echo $otherAsset;?></th>
	            		</tr>
	            		<?php foreach ($nonCurrentAssets as $nca):?>
	            		<tr>
	            			<td style="text-align: left;padding-left: 50px;"><?php echo $nca->name;?></td>
	            			<td style="text-align: right;padding-right: 50px;"><?php echo $nca->amount;?></td>
	            		</tr>
	            		<?php endforeach;?>
	            		<tr>
	            			<th style="text-align: center;">Total Asset</th>
	            			<th style="text-align: right;"><?php echo $asset;?></th>
	            		</tr>
	            		<tr>
	            			<th style="text-align: left;">Liability</th>
	            			<th style="text-align: right;"></th>
	            		</tr>
	            		<tr >
	            			<th style="text-align: left;padding-left: 25px;">Current Liability</th>
	            			<th style="text-align: right;padding-right: 25px;"><?php echo $currentLiability;?></th>
	            		</tr>
	            		<?php foreach ($currentLiabilities as $cl):?>
	            		<tr>
	            			<td style="text-align: left;padding-left: 50px;"><?php echo $cl->name;?></td>
	            			<td style="text-align: right;padding-right: 50px;"><?php echo $cl->amount;?></td>
	            		</tr>
	            		<?php endforeach;?>
	            		<tr >
	            			<th style="text-align: left;padding-left: 25px;">Other Liability</th>
	            			<th style="text-align: right;padding-right: 25px;"><?php echo $otherLiability;?></th>
	            		</tr>
	            		<?php foreach ($nonCurrentLiabilities as $ncl):?>
	            		<tr>
	            			<td style="text-align: left;padding-left: 50px;"><?php echo $ncl->name;?></td>
	            			<td style="text-align: right;padding-right: 50px;"><?php echo $ncl->amount;?></td>
	            		</tr>
	            		<?php endforeach;?>
	            		<tr>
	            			<th style="text-align: center;">Current + non-current liability</th>
	            			<th style="text-align: right;"><?php echo $liability;?></th>
	            		</tr>
	            		<tr >
	            			<th style="text-align: left;padding-left: 25px;">Equity</th>
	            			<th style="text-align: right;padding-right: 25px;"><?php echo $equity;?></th>
	            		</tr>
	            		<tr>
	            			<th style="text-align: center;">Total Liability</th>
	            			<th style="text-align: right;"><?php echo $asset;?></th>
	            		</tr>
					</table>
				</div>
			</div>
            <!----TABLE LISTING ENDS--->
		</div>
	</div>