<!doctype html>

<html>
    <head>
        <meta charset="utf-8"/>
       <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="description" content=""/>
        <meta name="viewport" content="width=device-width"/>
		
		 <?php include 'includes_top.php';?>
        <title><?php echo $page_title;?> | <?php echo $system_title;?></title>
		
    </head>
    <body>
        <?php include 'header.php';?>
        <div class="container-fluid" id="main-container">
            <div id="main-content" >
            	
                 <?php include 'page_info.php';?>  
                
                <?php	if(isset($success_message) && count($success_message)>0){?>
				<ul class="success message">
					<?php foreach ($success_message as $message):?>
					<li><?php echo $message;?></li>
					<?php endforeach;?>
				</ul>
				  <?php }	if(isset($fail_message) && count($fail_message)>0){?>
				<ul class="fail message">
					<?php foreach ($fail_message as $message):?>
					<li><?php echo $message;?></li>
					<?php endforeach;?>
				</ul>
				<?php }?>