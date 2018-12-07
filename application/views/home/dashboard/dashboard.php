
<div class="row">
<?php
foreach ( $menu as $k => $m ) :
	?>
	<div class="col-md-4">
		<h2><?php echo $k;?></h2>
			<?php 
			foreach ($m as $r):
				if($r->isMenu == 1){?>
			<div class="row">	
				<div class="col-md-12">	
	
						<a class="btn btn-default btn-block" href="<?php echo base_url();?>index.php/<?php echo $r->actionUrl;?>">
							<i class="icon-lock"></i><span><?php echo $r->displayName;?></span>
						</a>
		
					
				</div> 
			</div>
			<?php }
			endforeach;?>
	</div>
<?php endforeach;?>
</div>