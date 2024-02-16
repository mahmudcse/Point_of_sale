<?php
/*
 * This is generic search page. It uses '$searchAction', '$addmodifyAction', $searchDisplayTxt and $propertyArr
 * it calls getObjectPropertyValue($obj, $property) to get the display text from the object It will be varied based on various search page
 *
 * For custom search we need to implement by own
 *
 * Created on
 * Created by Sharif Uddin
 */
?> 
<style>
<!--
input{max-width: 200px;}
-->
</style>



<div class="box top-buffer">

	<div class="box-header">

<!-- CONTROL TABS START -->

		<ul class="nav nav-tabs nav-tabs-left">

			<li class="active">
            	<a href="#list" data-toggle="tab"><i class="icon-align-justify"></i> 
					<?php echo ucfirst($component).' List';?>
				</a>
 			</li>


		</ul>

    	<!-- CONTROL TABS END -->

</div>		

<div class="box-content padded">

		<div class="tab-content">            

            <!-- TABLE LISTING STARTS -->

            <div class="tab-pane box active" id="list">
            
            <div class="container">
			<?php 
				$attributes = array('name' => 'searchform');
				echo form_open($searchAction, $attributes);
				$hdnCnt = 0;
			 
				foreach ($inputs as $inp):
					
					if($inp['type'] == 'hidden'){
						echo form_hidden( $inp['fielddata']['name'],$inp['fielddata']['value']);
						$hdnCnt++;
					}
				endforeach;
				$cnt = 0;
				foreach ($inputs as $inp): 
					if($inp['type'] != 'hidden'){
						if($cnt%3==0){
				?>
							<div class="row top-buffer">
				<?php 	}?>
						<div class="col-md-1 right">
							<label> <?php echo $inp['label'];?> </label>
						</div>
						<div class="col-md-3"> 
							
							<?php if($inp['type']=='textfield')	{
								echo form_input($inp['fielddata']);
								$inp['fielddata']['class'] = "form-control";
							}
							elseif ($inp['type']=='dropdown')	{
								echo form_dropdown($inp['fielddata']['name'], $inp['fielddata']['options'], $inp['fielddata']['value'], 'class="form-control"');
							}
							else echo 'Type<>field map does not exist for type '.$inp['type'];?>
							
						</div>
				<?php if($cnt%3==2 || $cnt == count($inputs) - $hdnCnt-1){?>
				</div>
				<?php }?>
			<?php
			$cnt++;
			 }
			 endforeach;
			 ?>
				
			
			<div class="row  top-buffer" id="btnsearch">
				<div class="col-md-4">
					<input type="button" class="btn btn-default" onclick="this.form.pageNo.value=1;this.form.submit();" value="Search">
				</div>
			</div>
			
			<?php echo form_close();?>
			</div>

				

              
<?php

$searchText = '';
$resultCount = 0;
$arr = $searchData;

$lastPage = 1;

if($arr != null)
{
	$resultCount = count($arr);
}


$from = ($pageNo-1) * $limit + 1;
$to = $pageNo * $limit;

if($resultCount < $limit)
{
	$to = $to - ($limit - $resultCount);
}
$lastPage = (int) $total / $limit;

$nextUrl = $searchAction . '?search='.$searchText.'&pageNo='.($pageNo + 1);
$prevUrl = $searchAction . '?search='.$searchText.'&pageNo='.($pageNo - 1);

$colCount = count($propertyArr);

?>

<div id="search_result">
	<div class="row">
		<div class="col-md-12" style="text-align: right;">
		<?php 

		$paginationRow = '';
		if($pageNo > 1)
		{
			$paginationRow .= '<a href="#" onclick="document.searchform.pageNo.value=1;document.searchform.submit();">First</a>&nbsp;';
			$paginationRow .= '<a href="#" onclick="document.searchform.pageNo.value=document.searchform.pageNo.value-1;document.searchform.submit();">Prev</a>&nbsp;';
		}
		
		$paginationRow .= '&nbsp;&nbsp;<b>'.$from.'-'.$to . '</b> of <b>'.$total.'</b>&nbsp;&nbsp;';
		
		if($pageNo<$lastPage)
		{
			$paginationRow .= '<a href="#" onclick="document.searchform.pageNo.value=document.searchform.pageNo.value-(-1);document.searchform.submit();">Next</a>&nbsp;';
			$paginationRow .= '<a href="#" onclick="document.searchform.pageNo.value='.$lastPage.';document.searchform.submit();">Last</a>&nbsp;';
		}
		?>
			<label><?php echo $paginationRow;?></label>
		</div> 
	</div>


<table>

<?php

	/**************Start Column Header Row ***********/

	$columHeaderRow = '<tr>';

	foreach($propertyArr as $prop=>$value)

	{

		$columHeaderRow .= '<th>'.$value.'</th>';

	}	

	$columHeaderRow .= '<th>Action</th>';

	$columHeaderRow .= '</tr>';

	echo $columHeaderRow;

	/**************End Column Header Row ***********/



	

	if($arr != null && count($arr) > 0)

	{
		foreach ($arr as $value ) 

		{

			$modifylnk = '<a href="'.base_url($addmodifyAction).'/'. $value->componentId.'">';

			$dataRow = '<tr style="background-color:white;">';



			foreach($propertyArr as $prop=>$name)

			{

				$displayText = $value->{$prop};;

				$dataRow .= '<td><b>' . $modifylnk . Applicationconst::checkAndConv($displayText).'</b></a></td>';

			}

			

			$installmentId = $value->installmentId;

			$totalAmount = "SELECT
								sum(td.quantity * td.unitPrice) totalAmount
								FROM
								transaction_detail td
								INNER JOIN installment_reconcile ir ON ir.transaction_detail_id = td.componentId
								INNER JOIN installment i ON i.componentId = ir.installmentId
								WHERE i.componentId = $installmentId";
			$totalAmount = $this->db->query($totalAmount)->row()->totalAmount;

			$installmentAmount = $value->amount;

			if($installmentAmount <= $totalAmount){
				$dataRow .= '<td>
								<a class="btn btn-success btn-sm">Paid</a>
							</td>';
						}else{
							$dataRow .= '<td>
											<a href="'.base_url('installment/payments/'.$value->componentId.'/'.$value->installmentId.'').'" class="btn btn-default btn-sm" target="_blank">Reconcile</a>
										</td>';
						}

			$dataRow .= '</tr>';

			echo $dataRow;			

		}

	}

?>

	

</table>

<div class="row top-buffer"> 
	<div class="col-md-4">
		<input type="button" class="btn btn-default" value="Print search Result" onclick="printDiv('search_result');"/>
	</div>
	
</div>

</div>



			</div>

            <!----TABLE LISTING ENDS--->

			<!----CREATION FORM STARTS---->

			<div class="tab-pane box" id="add" style="padding: 5px">

                <div class="box-content">

                	&nbsp;&nbsp;&nbsp;

					<a style="color:#0000FF" href = "<?php echo base_url($addmodifyAction);?>"><?php echo get_phrase('Add New');?></a>
                </div>                

			</div>

			<!-- CREATION FORM ENDS -->

		</div>

	</div>



</div>
<hr />


<iframe name="print_frame" width="0" height="0" frameborder="0" src="about:blank"></iframe>



<script type="text/javascript">

<!--

printDivCSS = new String ('<link href="myprintstyle.css" rel="stylesheet" type="text/css">')

            function printDiv(divId) {

                window.frames["print_frame"].document.body.innerHTML=printDivCSS + document.getElementById(divId).innerHTML;

                window.frames["print_frame"].window.focus();

                window.frames["print_frame"].window.print();

            }
//-->

</script>

