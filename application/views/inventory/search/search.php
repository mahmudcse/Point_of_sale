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
<div class="row">
	<div class="col-md-12">
		<h2><?php echo $page_title; ?></h2>
	</div>
</div>

<style>
<!--
input{max-width: 200px;}
-->
</style>
<div id="content">
<?php
$attributes = array('name' => 'searchform');
echo form_open($searchAction, $attributes);?>

<?php foreach ($inputs as $inp):?>
<?php if($inp['type'] == 'hidden'){echo form_hidden( $inp['fielddata']['name'],$inp['fielddata']['value']);}?>	<?php endforeach;?>
<?php
$cnt = 0;
foreach ($inputs as $inp):
if($inp['type'] != 'hidden'){?>
	<?php if($cnt%3==0){?>
	<div class="row">
	<?php }?>
	<div class="col-md-4">
		<h4><label class="label label-default"> <?php echo $inp['label'];?> </label></h4>
		<?php if($inp['type']=='textfield')	echo form_input($inp['fielddata']);
		elseif ($inp['type']=='dropdown')	echo form_dropdown($inp['fielddata']['name'], $inp['fielddata']['options'], $inp['fielddata']['value']);
		else echo 'Type<>field map does not exist for type '.$inp['type'];?>
	</div>
	<?php if($cnt%3==2 || $cnt == count($inputs)-1){?>
	</div>
	<?php }?>
<?php
$cnt++;
 }
 endforeach;
 ?>



<div class="row">
	<input type="button" class="btn btn-primary" onclick="this.form.pageNo.value=1;this.form.submit();" value="Search">
</div>

<div style="clear: both;"></div>
<?php echo form_close();?>
</div>
<div class="box">
	<div class="box-header">
<!-- CONTROL TABS START -->
		<ul class="nav nav-tabs nav-tabs-left">
			<li class="active">
            	<a href="#list" data-toggle="tab"><i class="icon-align-justify"></i>
					<?php echo ucfirst($component).' List';?>
                    	</a></li>
			<li>
            	<a   onmouseover="this.style.color='#000'" onmouseout="this.style.color='#fff'" style="float:left; color: #fff" href = "<?php echo base_url($addmodifyAction);?>"><i class="icon-plus"></i>&nbsp;<?php echo 'Add '.ucfirst($component);?></a>
               	<!--<a href="#add" data-toggle="tab"><i class="icon-plus"></i>
					<?php //echo 'Add '.ucfirst($component);?>
                 </a>
				 -->

				 </li>
		</ul>
    	<!-- CONTROL TABS END -->
</div>
<div class="box-content padded">
		<div class="tab-content">
            <!-- TABLE LISTING STARTS -->
            <div class="tab-pane box active" id="list">


<?php

$searchText = '';
//$pageIndex = 0;
//$limit = 10;
$resultCount = 0;
$arr = $searchData;
$lastPage = 1;
//$total = $service->getCountByCode($searchText);
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
<table width="100%" border="1" style="border-collapse:collapse;">
<?php

	/**************Start Pagination Row ***********
	$paginationRow = '<tr>';
	for($i = 0; $i <($colCount - 6); $i++)
	{
		$paginationRow .= '<td/>';
	}

	$paginationRow .= '<td colspan="6" align="right">';
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
	$paginationRow .= '</td></tr>';
	echo $paginationRow;
	**************End Pagination Row ***********/


	/**************Start Column Header Row ***********/
	$columHeaderRow = '<tr>';
	foreach($propertyArr as $prop=>$value)
	{
		$columHeaderRow .= '<th>'.$value.'</th>';
	}
	$columHeaderRow .= '</tr>';
	echo $columHeaderRow;
	/**************End Column Header Row ***********/


	if($arr != null && count($arr) > 0)
	{
		$totalQty = 0;
		foreach ($arr as $value )
		{
			$modifylnk = '<a style = "text-decoration:none;" href="'.base_url($addmodifyAction).'/'. $value->componentId.'">';

			$dataRow = '<tr style="background-color:white;">';
			$col = 0;
			$totalQty += $value->quantity;
			foreach($propertyArr as $prop=>$name)
			{
				$displayText = $value->{$prop};;
				$quantity = $value->quantity;

				//$minQty = $value->minQty;
				$col=$col+1;

				$dataRow .= '<td><b>' . $modifylnk . Applicationconst::checkAndConv($displayText).'</b></a></td>';
			
			}
			$dataRow .= '</tr>';
			echo $dataRow;
		}
	}
?>
	<tr>
		<th colspan="<?php echo count($propertyArr)-5;?>"> Total </th>
		<th><?php //echo Applicationconst::checkAndConv($summeryData->totalQuantity);?></th>
		<th><?php 
			if(isset($totalQty))
				echo $totalQty; 
		?></th>
		<th><?php //echo Applicationconst::checkAndConv($summeryData->totalSoldQuantity);?></th>
		<!-- <th><?php //echo Applicationconst::checkAndConv($summeryData->totalPurchasedAmount);?></th> -->
		<!-- <th><?php //echo Applicationconst::checkAndConv($summeryData->totalSoldAmount);?></th> -->
	</tr>
</table>
</div>
<div>

	<input type="button" onclick="printDiv('search_result');" value=" Print " style="padding:10px"/>
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
			<!----CREATION FORM ENDS--->

		</div>
	</div>

</div>


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
