<div id="rec">
	<table class="rpt-table">
		<tr>
			<td class="rpt-center" colspan="<?php echo count($propertyArr);?>">
				<h1>Receivable</h1>
			Date: <?php echo $stdate.' to '.$etdate;?>
	        <br />
			<br />
			</td>
		</tr>
								<?php
								
								/**
								 * ************Start Column Header Row **********
								 */
								$columHeaderRow = '<tr class="rpt-header">';
								foreach ( $propertyArr as $prop => $value ) {
									$columHeaderRow .= '<th>' . $value . '</th>';
								}
								$columHeaderRow .= '</tr>';
								echo $columHeaderRow;
								/**
								 * ************End Column Header Row **********
								 */
								$totalRec = 0.0;
								$totalOb = 0.0;
								$totalDr = 0.0;
								$totalCr = 0.0;
								if ($recData != null && count ( $recData ) > 0) {
								
									foreach ( $recData as $value ) {
										
										$modifylnk = '<a href="showledger?accountId=' . Applicationconst::ACCOUNT_HEAD_RECEIVABLE . '&userId=' . $value->componentId . '">';
										
										if($value->amt == 0 && $value->dr == 0 && $value->cr == 0){
											continue;
										}
										$totalRec += $value->amt;

										$totalOb += $value->ob;
										$totalDr += $value->dr;
										$totalCr += $value->cr;
										$dataRow = '<tr class="rpt-body">';
										foreach ( $propertyArr as $prop => $name ) {
											$displayText = $value->{$prop};
											;
											$dataRow .= '<td>' . $modifylnk . $displayText . '</a></td>';
										}
										$dataRow .= '</tr>';
										
											echo $dataRow;
										
										
									}
								}
								?>	
									<tr>
			<th colspan="<?php echo count($propertyArr)-4;?>">Total</th>
			<th><?php echo $totalOb;?></th>
			<th><?php echo $totalDr;?></th>
			<th><?php echo $totalCr;?></th>
			<th><?php echo $totalRec;?></th>
		</tr>
	</table>
</div>