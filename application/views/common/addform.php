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


<div class="box top-buffer">

	<div class="box-header">

<!-- CONTROL TABS START -->

		<ul class="nav nav-tabs nav-tabs-left">

			<li >
            	<a href = "<?php echo base_url('index.php/'.$component.'/search');?>"><i class="icon-align-justify"></i>
					<?php echo ucfirst($component).' List';?>
				</a>
			</li>

			<li class="active" >
            	<a data-toggle="tab" href = "#add"><i class="icon-plus-justify"></i>
            		<?php echo 'Add '.ucfirst($component);?>
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

					<?php echo form_open($component.'/save');
						$hdnCnt = 0;
						foreach ($inputs as $inp):
							if($inp['type'] == 'hidden'){
								$hdnCnt++;
								echo form_hidden( $inp['fielddata']['name'],$inp['fielddata']['value']);
							}
						endforeach;?>
					<?php //$this->load->view('templates/buttonbar.php');?>
					<br/>
					<div class="container">
						<?php
						$cnt = 0;
						foreach ($inputs as $inp):?>
							<?php if($inp['type'] != 'hidden'){

								if($cnt%3 == 0){
							?>
								<div class="row top-buffer">
							<?php } ?>

									<div class="col-md-1 right">
										<label for="<?php echo $inp['fielddata']['name'];?>"><?php echo isset($inp['label'])?$inp['label']:$inp['fielddata']['name'];?></label>
									</div>
									<div class="col-md-3">

									<?php

										if($inp['type']=='textfield'){
											$inp['fielddata']['class'] = "form-control";
											echo form_input($inp['fielddata']);
										}
										elseif ($inp['type']=='dropdown'){
											echo form_dropdown($inp['fielddata']['name'], $inp['fielddata']['options'], $inp['fielddata']['value'],'class = "form-control"');
										}
										else
											echo 'Type<>field map does not exist for type '.$inp['type'];
									?>
									</div>
								<?php if($cnt%3 == 2 || $cnt == count($inputs) - 1 - $hdnCnt ){?>
									</div>
								<?php }
								$cnt++;
								?>
						<?php }?>
					<?php endforeach;?>


					<?php $this->load->view('templates/buttonbar.php');?>
					</div>

			<?php echo form_close();?>
						<br />
						<hr />


                </div>

			</div>

			<!----CREATION FORM ENDS--->

		</div>
	</div>
</div>
