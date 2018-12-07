<!--
        <div class="page-title">

			<div>

				<h1>

                	<img src="<?php echo base_url();?>template/images/icon_right.png" style="width:25px;" />

					<?php echo $page_title;?>

               </h1>

			</div>

		</div>

        -->

        <!--------FLASH MESSAGES--->

        

		<!--<?php if($this->session->flashdata('flash_message') != ""):?>

        <div class="container-fluid padded">

        	<div class="alert alert-info">

              <button type="button" class="close" data-dismiss="alert">×</button>

              <?php echo $this->session->flashdata('flash_message');?>

            </div>

        </div>

        <?php endif;?>-->
        <?php if($this->session->flashdata('flash_message') != ""):?>

 		<script>

			$(document).ready(function() {

		    Growl.info({title:"<?php echo $this->session->flashdata('flash_message');?>",text:" "})

			});

		</script>

        <?php endif;?>