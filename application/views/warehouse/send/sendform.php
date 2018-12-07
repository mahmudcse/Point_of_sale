
<?php echo form_open("warehouse/send");?>
<input type="hidden" name="operation" value="<?php echo $operation;?>"/>
<input type="hidden" name="size" id="size" />


<div class="container">
  <div class="row top-buffer">
  	<div class="col-md-1 right"><label>Date : </label></div>
    <div class="col-md-2">
    	<input type="text" class="form-control dtpkr" value="<?php echo $tdate;?>" name="tdate"/>
    </div>
    <div class="col-md-1 right"><label>From : </label></div>
    <div class="col-md-2">
    	<select class="form-control"  name="source">
    		<?php foreach ($sourceOptions as $option):?>
    		<option value="<?php echo $option->componentId;?>"><?php echo $option->name;?></option>
    		<?php endforeach;?>
    	</select>
    </div>
    <div class="col-md-1 right"><label>To : </label></div>
    <div class="col-md-2">
    	<select class="form-control" name="destination">
    		<?php foreach ($destinationOptions as $option):?>
    		<option value="<?php echo $option->componentId;?>"><?php echo $option->name;?></option>
    		<?php endforeach;?>
    	</select>
    </div>
     
  </div>
  <div class="row top-buffer">
  	<div class="col-md-2 right"><label>Description: </label></div>
      <div class="col-md-3">
      	<textarea class="form-control" name="description" rows="3" cols="15"><?php echo $description;?></textarea>
      </div>
      <div class="col-md-2 right"><label>Operation: </label></div>
      <div class="col-md-2">
      	<input type="text" class="form-control" readonly="readonly" name="type" value="<?php echo $type;?>"/>
      </div>
  </div>
  <div class="row"> &nbsp;</div>
</div>

<div id="table-content-header" class="container">
	<div class="row bottom-border top-buffer">
	    <div class="col-md-1"><label>S/L No.</label></div>
	    <div class="col-md-4"><label>Product</label></div>
	    <div class="col-md-3"><label>Quantity</label></div>
	    <div class="col-md-3"><label>Condition</label></div>
	    <div class="col-md-1">
	    	<button type="button" onclick="addRow()" class=" btn btn-default glyphicon glyphicon-plus">
	    		
	    	</button>
	    </div>
 	 </div>
</div>
<div id="table-content-body" class="container"></div>
<div id="table-content-body" class="container">
	<div class="row top-buffer">
		<div class="col-md-1"><button type="submit" class=" btn btn-default"> Save </button></div>
	</div>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">

var options = {
	    source: [
			<?php foreach ($items as $item):?>
	          {
	            value: "<?php echo $item->componentId;?>",
	            label: '<?php echo $item->itemName;?>',
	            desc: "<?php echo $item->category1." | ".$item->category2." | ".$item->category3;?>"
	          },
	          <?php endforeach;?>
		    ],
	    minLength: 2,
	    select: function( event, ui ) {
		    var index = $(this).attr('id');
		    index = index.replace("prdid-", "");
		    data[index].name = ui.item.label;
		    data[index].id = ui.item.value;
		    renderIt();
	    }
	};
	
	var data = [];
	
	function addRow(){
		data.push({id:"", name:"", quantity:"1", condition:"Good"});
		renderIt();
	}

	function removeRow(indx){
		if(confirm("Are you sure to delete this item?")){
			data.splice(indx, 1);
		}

		renderIt();
	}

	function renderIt(){
		document.getElementById('size').value = data.length;

		var node = document.getElementById('table-content-body');
		


		while (node.hasChildNodes()) {
			node.removeChild(node.childNodes[0]);
		}



		
		for(var i=0;i<data.length;i++){
			var rowElement = document.createElement("div");

			

			rowElement.className = "row bottom-border-dotted";
			
			var col1 = document.createElement("div");
			col1.className = "col-md-1";
			col1.innerHTML = ""+(i-(-1));
			rowElement.appendChild(col1);
			
			var col2 = document.createElement("div");
			col2.className = "col-md-4";
			col2.innerHTML = "<input type=\"hidden\" class=\"form-control\" name=\"itemId" + i + "\" value=\"" + data[i].id + "\"/>" +
			"<input class=\"form-control\" name=\"name" + i + "\" id=\"prdid-" + i + "\" value=\"" + data[i].name + "\"/>";
			rowElement.appendChild(col2);
			
			var col3 = document.createElement("div");
			col3.className = "col-md-3";
			col3.innerHTML = "<input class=\"form-control productName\" onchange=\"changeQuantity(" + i + " ,this.value)\" name=\"quantity" + i + "\" value=\"" + data[i].quantity + "\"/>";
			rowElement.appendChild(col3);
			
			var col4 = document.createElement("div");
			col4.className = "col-md-3";
			html = "<select onchange=\"changeCondition(" + i + " ,this.options[this.selectedIndex].value);\" class=\"form-control\"  name=\"condition" + i + "\">";
			if(data[i].condition=='Good'){
				html += "<option selected=\"selected\" value=\"Good\"> Good </option>";
				html += "<option value=\"Damaged\"> Damaged </option>";
			}else {
				html += "<option value=\"Good\"> Good </option>";
				html += "<option selected=\"selected\" value=\"Damaged\"> Damaged </option>";
			}
			html += "</select>";
			col4.innerHTML = html;
			rowElement.appendChild(col4);

			var col5 = document.createElement("div");
			col5.className = "col-md-1";
			col5.innerHTML = "<button onclick=\"removeRow(" + i + ");\" class=\" btn btn-default glyphicon glyphicon-remove\"></button>";
			rowElement.appendChild(col5);
			
			node.appendChild(rowElement);

			$("#prdid-" + i).autocomplete(options);

			
		}
	}

	function changeQuantity(index, value){
		data[index].quantity = value;
	    renderIt();
	}

	function changeCondition(index, value){
		data[index].condition = value;
	    renderIt();
	}

</script>
