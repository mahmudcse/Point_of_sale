<div id="pay">
	<table class="rpt-table">
		<tr >
			<td class="rpt-center" colspan="<?php echo count($propertyArr);?>">
				<h1>Payable</h1>
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
								$totalPay = 0.0;
								if ($payData != null && count ( $payData ) > 0) {
									foreach ( $payData as $value ) {
										$modifylnk = '<a href="showledger?accountId=' . Applicationconst::ACCOUNT_HEAD_PAYABLE . '&userId=' . $value->componentId . '">';
										if($value->amt == 0 && $value->dr == 0 && $value->cr == 0){
											continue;
										}
										$totalPay += $value->amt;
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
			<th colspan="<?php echo count($propertyArr)-1;?>">Total</th>
			<th><?php echo $totalPay;?></th>
		</tr>
	</table>
</div>