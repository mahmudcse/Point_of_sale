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
<div id="content">

<?php echo form_open($searchAction);?>
	
	<table style="width:80%;margin: auto;">
			<tr>
				<th>Account</th>
				<th>User Id</th>
				<th>Start Date</th>
				<th>End Date</th>
				<th>Option</th>
			</tr>
			<tr>	
				<td><?php echo form_dropdown('accountId', $account, $accountId);?></td>
				<td><?php echo form_dropdown('userId', $customer, $userId);?></td>
				<td><?php echo form_input('stdate',$stdate);?> </td>
				<td> <?php echo form_input('etdate',$etdate);?></td>
				<td><input type="submit" id="addProduct" style="padding: 10px;" value="Show"/></td>
			</tr>
			
	</table>	
<?php echo form_close();?>
</div>
<div class="box">
	<div class="box-header">
<!------CONTROL TABS START------->
		<ul class="nav nav-tabs nav-tabs-left">
			<li class="active">
            	<a href="#list" data-toggle="tab"><i class="icon-align-justify"></i> 
					Ledger
               	</a>
           	</li>
		</ul>
    	<!------CONTROL TABS END------->
	</div>		

<?php $this->load->view('ledger/search/content.php');?> 
</div>
<?php echo form_open($downloadAction);?>
			
	<?php echo form_hidden('accountId',  $accountId);?>
	<?php echo form_hidden('userId', $userId);?>
	<?php echo form_hidden('stdate',$stdate);?>
	<?php echo form_hidden('etdate',$etdate);?>
	<input type="button" onclick="printDiv('search_result');" value=" Print " style="padding:10px"/>
	<input type="submit" value="PDF Download" style="padding:10px"/>
	
<?php echo form_close();?>
 <iframe name="print_frame" width="0" height="0" frameborder="0" src="about:blank"></iframe>
	<script type="text/javascript">
            printDivCSS = new String ('<link href="myprintstyle.css" rel="stylesheet" type="text/css">')
            function printDiv(divId) {
                window.frames["print_frame"].document.body.innerHTML=printDivCSS + document.getElementById(divId).innerHTML;
                window.frames["print_frame"].window.focus();
                window.frames["print_frame"].window.print();
            }
 	</script>