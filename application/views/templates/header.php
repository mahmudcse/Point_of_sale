 <nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#"><?php echo $system_name;?></a>
    </div>
    <ul class="nav navbar-nav">
    	<?php
			foreach ( $menu as $k => $m ) :
		?>
		<li class="dropdown">
        	<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo $k;?>
        		<span class="caret"></span>
        	</a>
        	<ul class="dropdown-menu">
        	<?php 
			foreach ($m as $r):
				if($r->isMenu == 1){
		
			?>
          		<li><a href="<?php echo base_url();?>index.php/<?php echo $r->actionUrl;?>"><?php echo $r->displayName;?></a></li>
          	<?php
				}
          	endforeach;
          	?>
        	</ul>
      	</li>
		<?php 
			endforeach;
		?>
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <li><a href="#"><span class="glyphicon glyphicon-user"></span><?php echo $username;?></a></li>
      <li><a href="<?php echo base_url('index.php/login/logout')?>"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
    </ul>
  </div>
</nav>
