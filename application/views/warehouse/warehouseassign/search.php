<div class="box">
	<div class="box-header">
		<!------CONTROL TABS START------->
		<ul class="nav nav-tabs nav-tabs-left">
			<li class="active">
				<a href="#list" data-toggle="tab">
					<i class="icon-align-justify"></i> 
					<?php echo ucfirst($component).' List';?>
                </a>
            </li>
		</ul>
		<!------CONTROL TABS END------->
	</div>
	<div class="box-content padded">
		<div class="tab-content">
			<!----TABLE LISTING STARTS--->
			<div class="tab-pane box active" id="list">
				
				
				<div id="search_result">
					<br/>
					<?php echo form_open($searchAction);?>
					
						<div class="row">
							<label class="col-md-2">Select user: </label>
							<div  class="col-md-6">
								<?php echo form_dropdown('userId',$user, $userId,"onchange=\"this.form.submit();\"");?>
							</div>
						</div>
						<div class="row">
							<label class="col-md-2">Select company: </label>
							<ul class="col-md-2 list-group">
							<?php
						
							$cnt = 0;
							if ($searchData != null && count ( $searchData ) > 0) {
								foreach ( $searchData as $value ) {
									$cnt ++?>							
									<li class="list-item">
										<input type="checkbox" id="company<?php echo $cnt;?>"<?php echo $value->assigned!=0?"checked":"";?> name="companies[]" value="<?php echo $value->componentId;?>" /> 
										&nbsp;&nbsp;
										<label style="display: inline;" for="company<?php echo $cnt;?>"><?php echo $value->name;?></label></li>				
							<?php
								}
							}
							?>
							</ul>
						</div>
						<div class="row">
							<div class="col-md-2">&nbsp;</div>
							<div class="col-md-6">
								<?php echo form_submit('assign','Assign',"class='btn btn-default'");?>
							</div>
						</div>
					<?php echo form_close();?>
					<br/>
				</div>
				
				
			</div>
		</div>
	</div>
</div>
