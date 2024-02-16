
<div class="box top-buffer">

	<div class="box-header">

<!-- CONTROL TABS START -->

		<ul class="nav nav-tabs nav-tabs-left">

			<li >
            	<a href = "<?php echo base_url('index.php/warehouse/receive');?>"><i class="icon-align-justify"></i> 
					<?php echo ucfirst($component).' List';?>
				</a>
			</li>

			<li class="active" >
            	<a data-toggle="tab" href = "#add"><i class="icon-plus-justify"></i> 
            		<?php echo 'Receive';?>
            	</a>
			</li>

		</ul>

    	<!-- CONTROL TABS END -->

	</div>		

	<div class="box-content padded">

		<div class="tab-content">            

            <!-- TABLE LISTING STARTS -->

            <div class="tab-pane box" id="list">
				
			</div>

         
			<div class="tab-pane box active" id="add">
				
				<div class="box-content">
					

					<div class="container">
						<div id="errormsg" style="color: red; padding: 5px; background: $ededed"><?php echo $this->session->flashdata('msg'); ?></div>
						<table class="table table-responsive table-hover">
							<thead>
								<tr>
									<td>#</td>
									<td>Item</td>
									<td>Quantity</td>
									<td>Condition</td>
									<td>Action</td>
								</tr>
							</thead>
							<tbody>

							<?php if(!empty($detailList)): ?>
								<?php foreach ($detailList as $key => $list): ?>
								<tr id="<?php echo $list['componentId']; ?>">
									<td><?php echo $key+1; ?></td>
									<td><?php echo $list['itemName']; ?></td>
									<td><?php echo $list['quantity']; ?></td>
									<td>
										<div class="col-md-5">
											<select name="condition" id="condition<?php echo $list['componentId']; ?>" class="form-control condition">
												<option value="Good"
													<?php if($list['condition'] == 'Good') echo 'selected'  ?>
												>
													Good
												</option>

												<option value="Damaged"
													<?php if($list['condition'] == 'Damaged') echo 'selected'  ?>
												>
													Damaged
												</option>
												<option value="Lost"
													<?php if($list['condition'] == 'Lost') echo 'selected'  ?>
												>
												Lost
												</option>
											</select>
										</div>	
									</td>
									<td>
									
										<?php 
											// $itemId      = $list['itemId'];
											// $detailId    = $list['componentId'];
											// $inventoryId = $list['inventoryId'];
											// $quantity    = $list['quantity'];

											$condition = array(
												'itemId'      => $list['itemId'],
												'inventoryId' => $list['inventoryId'],
												'type'        => '1'
											);

											$this->db->select('sum(quantity) totalReceived');
											$this->db->from('inventorydetail');
											$this->db->where($condition);
											$totalReceived = $this->db->get()->row()->totalReceived;
										?>

										<a href="javascript:;" class="btn 
											<?php 
												echo ($list['quantity'] == $totalReceived)?"btn-success":"btn-default rcvConfirm";
											?>
										" id="<?php echo $list['componentId']; ?>">Receive</a>
										
									<?php if($list['quantity'] != $totalReceived): ?>
										<button type="button" class="btn btn-info split" 
											id="<?php echo $list['componentId']; ?>" 
											quantity="<?php echo $list['quantity']; ?>" 
											name="<?php echo $list['itemName']; ?>" 
											quality="<?php echo $list['condition']; ?>" 
											inventoryId="<?php echo $list['inventoryId']; ?>"
											
											>
										Split
										</button>

									<?php endif; ?>


									</td>
								</tr>
								<?php endforeach; ?>
							<?php endif; ?>

							</tbody>
						</table>
						
   						

                	</div>                

				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Split Receive</h4>
      </div>
      <?php echo form_open('warehouse/batchReceive') ?>
      <div class="modal-body">
      
      	<input type="hidden" value="" id="detailId" name="detailId">
      	<input type="hidden" value="" id="invId" name="inventoryId">
			<table class="table table-responsive">
				<thead>
					<tr>
						<td>Item</td>
						<td>Quantity</td>
						<td>Quality</td>
						<td>Action</td>
					</tr>
				</thead>
				<tbody id="modalBody">
					
				</tbody>
			</table>
      </div>
      <div class="modal-footer">
      	<button type="submit" class="btn btn-success" value="">Receive</button>
        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>

  </div>
</div>

<div id="blankModalRow" hidden>
	<tr>
		<td>&nbsp;</td>
		<td>
			<input type="text" name="quantity[]">
		</td>
		<td>
			<div class="col-md-3">
				<select name="quality[]" id="" class="form-control">
					<option value="Good">Good</option>
					<option value="Damaged">Damaged</option>
					<option value="Lost">Lost</option>
				</select>
			</div>
			
		</td>
		<td>&nbsp;</td>
	</tr>
</div>

<script>

	$('#myModal').on('click', '.addRow', function(event) {
		event.preventDefault();

		var blankRow = '';
		blankRow += '<tr>'+
				'<td>&nbsp;</td>'+
				'<td>'+
					'<input type="text" name="quantity[]">'+
				'</td>'+
				'<td>'+
						'<select name="quality[]" id="" class="form-control">'+
							'<option value="Good">Good</option>'+
							'<option value="Damaged">Damaged</option>'+
							'<option value="Lost">Lost</option>'+
						'</select>'+
					
				'</td>'+
				'<td><a href="javascript:;" class="btn btn-default glyphicon glyphicon-minus removeRow"></a></td>'+
			'</tr>';
		
		//var blankRow = $('#blankModalRow').html();
		$('#modalBody tr:last').after(blankRow);
		console.log(blankRow);


	});

	$(document).on('click', '.removeRow', function(event) {
		event.preventDefault();
		
		$(this).parent().parent().remove();
	});

	$(document).on('click', '.split', function(event) {
		event.preventDefault();

		var invId = $(this).attr('inventoryId');
		$('#invId').attr('value', invId);
		

		var detailId = $(this).attr('id');
		$('#detailId').attr('value', detailId);

		var quantity = $(this).attr('quantity');
		var itemName = $(this).attr('name');
		var quality  = $(this).attr('quality');

		var table = '';
		table += '<tr>'+
						'<td>'+itemName+'</td>'+
						'<td>'+'<input type="text" name="quantity[]" readonly value="'+quantity+'"  />'+'</td>'+
						'<td>'+'<input type="text" name="quality[]" readonly value="'+quality+'"/>'+'</td>'+
						'<td>'+
							' <a href="javascript:;" class="btn btn-default glyphicon glyphicon-plus addRow"></a> '+
						'</td>'+
					'</tr>';

		$('#modalBody').html(table);
		$('#myModal').modal('show');
		
	});


	$(document).on('click', '.rcvConfirm' ,function(){
		var detailId = $(this).attr('id');
		var condition = $('#condition'+detailId).val();
		var url = "<?php echo base_url('index.php/warehouse/receiveConfirmation/') ?>"+detailId+'/'+condition;

		$(this).siblings('button').fadeOut();
		$(this).removeClass('btn-default');
		$(this).removeClass('rcvConfirm');
		$(this).addClass('btn-success');
		$(this).text('Received');

		$.ajax({
			method: 'POST',
			url: url,
			data: {detailId: detailId, condition: condition},
			dataType: 'json',
		});
	});

	$(window).on('load', function(event) {
		event.preventDefault();
		$('#errormsg').delay(1000).fadeOut();
	});
</script>
