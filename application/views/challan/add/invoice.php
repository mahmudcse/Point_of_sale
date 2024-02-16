<table style="width: 100%;">
						<tr>
						<td width="40%" valign="top" colspan="2">
							<img src="<?php echo base_url('assets/images/logo.png')?>" width="72"  height="94" style="float:left; padding-right:10px" />
							<div style="color:#008040; font-size:40px;padding:0px 0px 10px 0px; font-weight:bold"><?php echo $system_name; ?></div>
							<div style="font-size:12px;"><strong><?php //echo $company_function; ?></strong></div>
						</td>
						<td  width="30%" valign="top" style="text-align:left; padding-left:10px; font-size:13px" colspan="2">
							
							<table>
								<tr>
									<td colspan="3">
										<strong>Head Office </strong><br />
										<?php //echo $head_office_address; ?>
									</td>
								</tr>
								<tr>
									<td>Phone</td>
									<td> : </td>
									<td>
										<?php //echo $head_office_phone; ?>
									</td>
								</tr>
								<tr>
									<td>Fax</td>
									<td> : </td>
									<td><?php //echo $head_office_fax; ?></td>
								</tr>
								<tr>
									<td>Email</td>
									<td> : </td>
									<td><?php //echo $head_office_email1; ?></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td><?php //echo $head_office_email2; ?></td>
								</tr>
							</table>
						</td>
						<td width="30%" valign="top" align="left" style="text-align:left; padding-left:10px;font-size:13px">
							
							<table>
								<tr>
<td colspan="3"><strong><?php //echo $branch_office_name; ?></strong><br /><?php //echo $branch_office_address; ?></td>
								</tr>
								<tr>
									<td>TIN</td>
									<td> : </td>
									<td><?php //echo $tin; ?></td>
								</tr>
								<tr>
									<td>VAT</td>
									<td> : </td>
									<td><?php //echo $vat; ?></td>
								</tr>
								
								<tr>
									<td>AREA</td>
									<td> : </td>
									<td><?php //echo $vat; ?></td>
								</tr>
							</table>
							
						</td>
						
						</tr>
						<tr><td colspan="5"><hr /></td></tr>
						<tr><th colspan="5"><h2><u>INVOICE</u></h2></th></tr>
						
						<tr>
							<td colspan="5" style="padding-bottom:30px">
								<table style="width: 100%;">
									<tr><td colspan="3" height="20">&nbsp;</td></tr>
									<tr>
										<td style="width:60%;padding-right: 10px;" valign="top">
											<div style="border-bottom: 1px solid #ddd;">Bill To</div> 
											<strong><?php echo $billTo;?></strong><br/>
											<?php echo $billingAddress;?>
										</td>
										<td style="width:40%;padding-left: 10px;" valign="top">
											<div style="border-bottom: 1px solid #ddd;">Reference:</div> 
											<div>
												<strong><?php echo $invoiceRef;?> / <?php echo date('d-m-Y',strtotime($tdate));?></strong><br/>
												Transaction : <?php echo $transactionRef;?><br/>
												Date of Delivery : <?php echo date('d-m-Y',strtotime($deliverydate));?><br/>
											</div>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr><td colspan="5" valign="top" style="padding-bottom:50px; height:350px">
									<table width="100%">
										<tr style="border-collapse:collapse; border:1px solid #CCCCCC;background-color:#CCCCCC">
										<th width="12%">S/L</th>
										<th width="42%" style="text-align:left">Item</th>
										
										<th width="13%">Quantity</th>
										<th width="11%" style="text-align:right">Unit Price</th>
										<th width="22%" style="text-align:right">Amount</th>
									</tr>
									<?php $cnt = 0;?>
									<?php 

									foreach ($items as $item): 
										$cnt++;
									?>
									<tr style="border-collapse:collapse; border:1px solid #CCCCCC;border-bottom:1px solid #ddd">
										<td align="center"><?php echo $cnt;?></td>
										<td align="left"><?php echo $item->itemName;?></td>
										
										<td align="center"><?php echo $item->absquantity;?>&nbsp;<?php echo $item->unit;?></td>
										<td  align="right" style="padding-right:10px"><?php echo $item->unitPrice;?></td>
										<td align="right" bgcolor="#E5E5E5"><?php echo $item->amount;?></td>
									</tr>
									<?php endforeach;?>
									<tr>
										<td colspan="4" align="right" style="padding-right:10px"><strong>Total</strong> </td>
										<td align="right" bgcolor="#E5E5E5"><strong><?php echo Applicationconst::convertWord($total);?></strong></td>
									</tr>
									<tr>
										<td colspan="4" align="right" style="padding-right:10px"><strong>Discount</strong> </td>
										<td align="right" bgcolor="#E5E5E5"><strong><?php echo Applicationconst::convertWord($discount);?></strong></td>
									</tr>
									<tr>
										<td colspan="4" align="right" style="padding-right:10px"><strong>Payable</strong> </td>
										<td align="right" bgcolor="#E5E5E5"><strong><?php echo Applicationconst::convertWord($total-$discount);?></strong></td>
									</tr>
									<tr>
										<td colspan="4" align="right" style="padding-right:10px"><strong>Paid</strong> </td>
										<td align="right" bgcolor="#E5E5E5"><strong><?php echo Applicationconst::convertWord($paid);?></strong></td>
									</tr>
									<tr>
										<td colspan="4" align="right" style="padding-right:10px"><strong>Due</strong> </td>
										<td align="right" bgcolor="#E5E5E5"><strong><?php echo Applicationconst::convertWord($due);?></strong></td>
									</tr> 

								<?php foreach($installmentInfo as $info): ?>
									<tr>
										<td colspan="4" align="right" style="padding-right:10px"><strong>
											<?php echo $info['deadLine']; ?>
										</strong> </td>
										<td align="right" bgcolor="#E5E5E5"><strong>
											<?php echo Applicationconst::convertWord($info['amount']); ?>
												
											</strong></td>
									</tr>
								<?php endforeach; ?>
									
									 
									<tr>
										<td colspan="5" height="50">&nbsp;</td>
									</tr>


									<tr>
										<td colspan="5">
										In Words : <strong><?php echo Applicationconst::convert_number($total-$discount);?> Taka</strong>
										</td>
									</tr>
								</table>
						</td>
						</tr>
						<tr><td  colspan="5">&nbsp;</td></tr>
						<tr>
							<td colspan="5">
								<table width="100%">
									<tr>
										<td width="20%" align="center" style="border-top: 1px solid #000;"><strong>Received By</strong></td>
										<td width="5%">&nbsp;</td>
										<td width="20%" align="center" style="border-top: 1px solid #000;"><strong>Authorized Signature</strong></td>
										<td width="5%">&nbsp;</td>
										<td width="20%" align="center" style="border-top: 1px solid #000;"><strong>For FCI</strong></td>
									</tr>
								</table>
								
							</td>
						</tr>
						
						<tr>
							<td colspan="5" align="center" style="padding-top:40px">
								<small>Thanks for your business | Software Developed By NetSoft Ltd.(01824412272)</small>							</td>
						</tr>
					</table>