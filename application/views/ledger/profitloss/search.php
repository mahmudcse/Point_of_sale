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
		
<div>
 <h2 style='text-align: center' class="pull-right"><?php echo $page_title; ?></h2>
<?php echo form_open($searchAction);?>



	<table style="width:80%;margin: auto;">

			<tr>
				<?php 
				$sdatedata = array(
						'name'        => 'stdate',
						'value'          =>$stdate,
						'class'       => 'dtpkr'
				);
				$edatedata = array(
						'name'        => 'etdate',
						'value'          =>$etdate,
						'class'       => 'dtpkr'
				);
				?>
				<td>From : <?php echo form_input($sdatedata);?> </td>

				<td>To: <?php echo form_input($edatedata);?></td>

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

					Profits &amp; Losses Statements

               	</a>

           	</li>

		</ul>

    	<!------CONTROL TABS END------->

	</div>

	
<?php $this->load->view('ledger/profitloss/content.php');?> 
</div>
<?php echo form_open($searchAction);?>
			
	<?php echo form_hidden('stdate',$stdate);?>
	<?php echo form_hidden('etdate',$etdate);?>
	<input type="button" onclick="printDiv('search_result');" value=" Print " style="padding:10px"/>
	<input type="submit" value="PDF Download" style="padding:10px"/>
	
<?php echo form_close();?>

<iframe name="print_frame" width="0" height="0" frameborder="0" src="about:blank"></iframe>
<script type="text/javascript">

function toogle(type){

	$("."+type).toggle();

}
printDivCSS = new String ('<link href="myprintstyle.css" rel="stylesheet" type="text/css">')

            function printDiv(divId) {

                window.frames["print_frame"].document.body.innerHTML=printDivCSS + document.getElementById(divId).innerHTML;

                window.frames["print_frame"].window.focus();

                window.frames["print_frame"].window.print();

            }


</script>
