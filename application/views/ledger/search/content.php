<style type="text/css">
				
            	table#rpt td { 
				    padding: 0 5px;
				    
				    border-bottom: 1px dotted #999999;
				}
				table#rpt tr:nth-child(2n+1) td {
					background-color:#EEEEEE;
				}
				table#rpt tr:nth-child(2n) td {
					background-color:#EEEEEE;
				}
				table#rpt th { 
				    padding: 0 5px;
				  border:1px solid #CCCCCC;
				    border-bottom: 1px dotted #999999;
				    border-radius:0;
				}
				table#rpt { 
				
				    border-spacing: 0;
				    border-collapse: separate;
					color:#333333;
				}
	</style>	
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

<div>Account : <strong><?php echo $account[$accountId];?></strong></div>
<div>User : <strong><?php echo $customer[$userId];?></strong></div>
<div>Period : <strong><?php echo date('d-m-Y',strtotime($stdate));?></strong> to <strong><?php echo date('d-m-Y',strtotime($etdate));?></strong></div>
<br />
<table id="rpt" style="width: 100%;margin: auto; border-collapse:collapse;">
            			
<?php

	/**************Start Column Header Row ***********/
	$columHeaderRow = '<tr>';
	foreach($propertyArr as $prop=>$value)
	{
		$columHeaderRow .= '<th style="border:1px solid #CCCCCC;background-color:#999999;text-align: center;" ><strong>'.$value.'</strong></th>';
	}	
	$columHeaderRow .= '</tr>';
	echo $columHeaderRow;
	/**************End Column Header Row ***********/

	?>
	<tr>
		<td colspan="<?php echo $colCount-1;?>" style="text-align: right; border:1px solid #CCCCCC">Balance</td>
		<td align="center" style="border:1px solid #CCCCCC; font-weight:bold"><?php echo $cb;?></td>
	</tr>
	<?php 
	if($arr != null && count($arr) > 0)
	{
		foreach ($arr as $value ) 
		{
		?>
		<tr style="border:1px solid #CCCCCC;">
		<?php
			foreach($propertyArr as $prop=>$name)
			{
				$displayText = $value->{$prop};;
				?>
				<td  style="border:1px solid #CCCCCC; font-weight:bold" align="center"><?php echo $displayText?></td>
			<?php } ?>
			</tr>
			<?php
			
						
		}
	}
?>
	<tr>
		<td colspan="<?php echo $colCount-1;?>" style="text-align: right;border:1px solid #CCCCCC">Opeing Balance</td>
		<td align="center" style="border:1px solid #CCCCCC; font-weight:bold"><?php echo $ob;?></td>
	</tr>
	
</table>
</div>


			</div>
            <!----TABLE LISTING ENDS--->
            
		</div>
	</div>
