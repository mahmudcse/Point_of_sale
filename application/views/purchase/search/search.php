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

<center>

<?php echo form_open($searchAction);?>
	
	<table width="70%">
			<tr>
				<th style="text-align:left">Start Date</th>
				<th style="text-align:left">End Date</th>
				<th></th>
			</tr>
			<tr>	
			<td><?php echo form_input('stdate',$stdate,'class="form-control dtpkr"');?> </td>
				<td><?php echo form_input('etdate',$etdate,'class="form-control dtpkr"');?></td>
				<td><input type="submit" id="addProduct" style="padding: 10px;" value="Show"/></td>
			</tr>
			
</table>	
	
	
<?php echo form_close();?>
</center>
</div>
<div class="box">
	<div class="box-header">
<!------CONTROL TABS START------->
		<ul class="nav nav-tabs nav-tabs-left">
			<li class="active">
            	<a href="#list" data-toggle="tab"><i class="icon-align-justify"></i> 
					<?php echo ucfirst($component).' List';?>
                    	</a></li>
			<li>
            	<a   style="float:left" href = "<?php echo base_url($addmodifyAction);?>"><i class="icon-plus"></i>&nbsp;<?php echo 'Add '.ucfirst($component);?></a>
               	<!--<a href="#add" data-toggle="tab"><i class="icon-plus"></i>
					<?php //echo 'Add '.ucfirst($component);?>
                 </a>
				 -->
				 
				 </li>
		</ul>
    	<!------CONTROL TABS END------->
</div>		
<div class="box-content padded">
		<div class="tab-content">            
            <!----TABLE LISTING STARTS--->
            <div class="tab-pane box active" id="list">
				
              
<?php

$searchText = '';
$pageIndex = 0;
$limit = 10;
$resultCount = 0;
if(isset($_POST['search']))
{
	$searchText = $_POST['search'];
}
elseif(isset($_GET['method']))
{
	$pageIndex = $_GET['page'];
	if($pageIndex <=0)
	{
		$pageIndex = 0;
	}
	$searchText = $_GET['search'];
}

$arr = $searchData;
//$total = $service->getCountByCode($searchText);
if($arr != null)
{
	$resultCount = count($arr);
}

$from = $pageIndex * $limit + 1;
$to = ($pageIndex + 1) * $limit; 
if($resultCount < $limit)
{
	$to = $to - ($limit - $resultCount);
}

$nextUrl = $searchAction . '?method=get&search='.$searchText.'&page='.($pageIndex + 1);
$prevUrl = $searchAction . '?method=get&search='.$searchText.'&page='.($pageIndex - 1);

$colCount = count($propertyArr);
 
?>

<div id="search_result">
<table>
<?php
	
	/**************Start Pagination Row ***********/
	$paginationRow = '<tr>';
	for($i = 0; $i <($colCount - 1); $i++)
	{
		$paginationRow .= '<td/>';
	}
	$paginationRow .= '<td align="right"><b>'.$from.'-'.$to . '</b> of <b>'.$total.'</b>&nbsp;&nbsp;';
	if($pageIndex > 0)
	{
		$paginationRow .= '<a href="'. $prevUrl.'">Prev</a>&nbsp;';
	}
	
	if($resultCount >= $limit)
	{
		$paginationRow .= '<a href="'. $nextUrl.'">Next</a>&nbsp;';
	}
	$paginationRow .= '</td></tr>';
	echo $paginationRow;	
	/**************End Pagination Row ***********/
	
	
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
		foreach ($arr as $value ) 
		{
			$modifylnk = '<a href="'.base_url($addmodifyAction).'">';
			
			$dataRow = '<tr style="background-color:white;">';
			foreach($propertyArr as $prop=>$name)
			{
				$displayText = $value->{$prop};;
				$dataRow .= '<td><b>' . $modifylnk . $displayText.'</b></a></td>';
			}
			$dataRow .= '</tr>';
			echo $dataRow;			
		}
	}
?>
	
</table>
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
