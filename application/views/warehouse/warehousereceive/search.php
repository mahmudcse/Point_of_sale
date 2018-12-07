<?php echo form_open("warehouse/receive");?>



<div class="container">
  <div class="row top-buffer">
<!--   	<div class="col-md-1 right"><label>Date : </label></div>
    <div class="col-md-2">
    	<input type="text" class="form-control dtpkr" value="<?php //echo $tdate;?>" name="tdate"/>
    </div> -->
    <div class="col-md-2 right"><label>Warehouses : </label></div>
    <div class="col-md-2">
    	<select class="form-control"  name="warehouse" onchange="this.form.submit()">
      <option value="">Select warehouse</option>
    		<?php foreach ($ownedWarehouses as $warehouse):?>
    		<option value="<?php echo $warehouse['warehouseId'];?>"><?php echo $warehouse['warehouseName'];?></option>
    		<?php endforeach;?>
    	</select>
    </div>
     
  </div>
  <div class="row"> &nbsp;</div>
</div>

<div id="table-content-header" class="container">
	<div class="row top-buffer">
	    <!-- <div class="col-md-1"><label>S/L No.</label></div>
	    <div class="col-md-2"><label>Date</label></div>
	    <div class="col-md-3"><label>Warehouse</label></div>
      <div class="col-md-3"><label>From</label></div>
	    <div class="col-md-3"><label>Action</label></div> -->
      <table class="table table-reponsive table-hover">
        <thead>
          <tr>
            <td><label>S/L No.</label></td>
            <td><label>Date</label></td>
            <td><label>Warehouse</label></td>
            <td><label>From</label></td>
            <td><label>Action</label></td>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($sentEntries as $key => $entry): ?>
          <tr>
            <td><?php echo $key+1 ?></td>
            <td>Date</td>
            <td>Date</td>
            <td>Date</td>
            <td>Date</td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
 	 </div>
</div>

<?php echo form_close(); ?>