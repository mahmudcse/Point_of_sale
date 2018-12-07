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
<style>
<!--
input{max-width: 200px;}
-->
</style>
<?php echo form_open($searchAction);?><div id="content">
<div style="width: 400px;float: left;">	<label>Select user: </label><?php echo form_dropdown('userId',$user, $userId,"onchange=\"this.form.submit();\"");?></div>
</div>
<div class="box">
	<div class="box-header">
<!------CONTROL TABS START------->
		<ul class="nav nav-tabs nav-tabs-left">
			<li class="active">
            	<a href="#list" data-toggle="tab"><i class="icon-align-justify"></i> 
					<?php echo ucfirst($component).' List';?>
                    	</a></li>
		</ul>
    	<!------CONTROL TABS END------->
</div>		
<div class="box-content padded">
		<div class="tab-content">            
            <!----TABLE LISTING STARTS--->
            <div class="tab-pane box active" id="list">
			<div id="search_result">			
				<ul>
				<?php					$cnt = 0;
					if($searchData != null && count($searchData) > 0)
					{
						foreach ($searchData as $value ) 
						{								$cnt++				?>							<li><input type="checkbox" id="role<?php echo $cnt;?>" <?php echo $value->assigned!=0?"checked":"";?> name="roles[]" value="<?php echo $value->componentId;?>"/> &nbsp;&nbsp;<label style="display: inline;" for="role<?php echo $cnt;?>"><?php echo $value->uniqueCode;?></label></li>				<?php 
						}
					}
				?>
					</ul>					<?php echo form_submit('assign','Assign');?>
				</div>
			</div>
		</div>
	</div>
</div><?php echo form_close();?>
