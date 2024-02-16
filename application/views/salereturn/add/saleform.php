
 <h2 style='text-align: center' class="pull-right"><?php echo $page_title; ?></h2>
<script type="text/javascript">

function prepareAction(formObj, action){

	if(formObj==null){

		alert('Undefined form');

		return;

	}

	if('delete' == action.toLowerCase()){

		if( confirm("Are you sure?")){

			formObj.action = '<?php echo base_url('index.php/'.$component.'/delete');?>';

		}else{

			return;

		}

	}else if('update' == action.toLowerCase()){

		formObj.action = '<?php echo base_url('index.php/'.$component.'/save');?>';

	}else  if('save' == action.toLowerCase()){

		formObj.action = '<?php echo base_url('index.php/'.$component.'/save');?>';

	}else  if('cancel' == action.toLowerCase()){

		formObj.action = '<?php echo base_url('index.php/'.$component.'/search');?>';

	}else{

		alert('Invalid action');

		return;

	}

	formObj.submit();

}

</script>

<style>

.item {

	width: 210px;

	border: 1px solid #DDDDDD;

	padding: 8px;

	text-align: center

}

  .ui-autocomplete-category {

    font-weight: bold;

    padding: .2em .4em;

    margin: .8em 0 .2em;

    line-height: 1.5;

  }

  #productTable tr{

  	border-bottom: 1px dotted gray;

  }

  input{

  	text-align: right;

  	width: 80px;

  }

  .hotitem{

  	position: relative;top:-50px;

  }

</style>

<div id="userinput">

	<?php echo form_open($component.'/save');?>

		<?php foreach ($inputs as $inp):?>

			<?php if(isset($inp['type']) && $inp['type'] == 'hidden'){echo form_hidden( $inp['fielddata']['name'],$inp['fielddata']['value']);}?>

		<?php endforeach;?>

	<input type="hidden" name="productCounter" id = "productCounter" value="0"/>

	<table style="width:100%;">

		<tr>

			<td colspan="2">

				<table style="width: 100%;">

					<tr>

						<th width="100px;">Add Item</th>

						<td><input name="findProduct" id="findProduct" style="text-align: left;" class="item"/></td>

					</tr>

				

					<tr>

						<td colspan="2">

							<?php foreach ($hotItem as $item):?>

								<div style="float: left; width: 100px;border: 1px solid silver;height: 100px;margin: 4px;cursor: pointer;" onclick="addItem('<?php echo $item->componentId;?>', '<?php echo htmlentities ($item->itemName);?>','<?php echo htmlentities ($item->category2);?>','<?php echo htmlentities ($item->category3);?>', '<?php echo $item->salePrice;?>', '<?php echo $item->unit;?>');">

									<img  alt="" src="<?php echo base_url('assets/images/default.png')?>" style="width:100px;"/>

									<div class="hotitem" title="<?php echo $item->uniqueCode;?>">

									<?php echo $item->itemName;?><br/><!--  

									<?php echo $item->category3;?><br/>

									<?php echo $item->category2;?><br/>-->

									TK: <?php echo $item->salePrice;?><br/>

									</div>

								</div>

							<?php endforeach;?>

						</td>

					</tr>

					

				</table>

			</td>

		</tr>

		<tr>

			<td style="border: 1px solid silver;vertical-align: top; width: 70%;">

				<table id="productTable" style="text-align: center;width:100%;" >

					<tr style="background-color: #D0D0D0;">

						<th><?php echo get_phrase('Product');?></th>

						<th><?php echo get_phrase('Unit Price');?></th>

						<th><?php echo get_phrase('Quantity');?></th>

						<th> </th>

					</tr>

				</table>

			</td>

			<td style="width: 30%;">

				<input type="hidden" name="totaldr" value="<?php echo count($account);?>">

				<table style="text-align: right;">

				

					<tr>

						<th align="right"><div align="right" style="padding-right: 20px">Date</div></th>

						<td align="right"><?php echo form_input($inputs['tdate']['fielddata']);?></td>

					</tr>

					<tr>

						<th style="text-align: right;padding-right: 20px">Customer</th>

						<td><?php echo form_dropdown('userId', $customer,$userId);?></td>

					</tr>

					<tr>

						<th ><div align="right" style="padding-right: 20px"><?php echo get_phrase('Amount');?></div></th>

						<td><input type="text" class="" id="amount" readonly="readonly" name="amount" value="1" /></td>

					</tr>

					<tr>

						<th ><div align="right" style="padding-right: 20px">Discount</div></th>

						<td> <input type="text" class="" id="discount" name="discount" value="0.00" onchange="calculateDiscount();"/> </td>

					</tr>

			        <?php

					$crCount = 0;

					foreach ( $account as $acId => $acName ) :

					$crCount ++;

					?>

			  		<tr>

						<th><div align="right" style="padding-right: 20px"><?php echo $acName;?><input type="hidden" readonly="readonly" name="accountIdc<?php echo $crCount;?>" id="accountIdc<?php echo $crCount;?>" value="<?php echo $acId?>" /> </div></th>

						<td><input type="text" name="quantityc<?php echo $crCount;?>" onchange="renderItem();" id="quantityc<?php echo $crCount;?>" value="0.00" /></td>

					</tr>

					<?php endforeach;?>

			             

			     	<tr>

						<th style="text-align: right;padding-right: 20px">Commnets </th>

						<td ><?php echo form_textarea($inputs['description']['fielddata']);?></td>

					</tr>

					<tr>

						<th  align="right"><div align="right" style="padding-right: 20px">Reference</div></th>

						<td  align="right"><?php echo form_input($inputs['name']['fielddata']);?></td>

					</tr>

				</table>

			</td>

		</tr>

	</table>

					

	<div align="right"> 

		<?php $this->load->view('templates/buttonbar.php');?>

		</div>

	<?php echo form_close();?>

	<br />

	<hr />



</div>



<script type="text/javascript">



$.widget( "custom.catcomplete", $.ui.autocomplete, {

    _create: function() {

      this._super();

      this.widget().menu( "option", "items", "> :not(.ui-autocomplete-category)" );

    },

    _renderMenu: function( ul, items ) {

      var that = this,

        currentCategory = "";

      $.each( items, function( index, item ) {

        var li;

        if ( item.category != currentCategory ) {

          ul.append( "<li class='ui-autocomplete-category'>" + item.category + "</li>" );

          currentCategory = item.category;

        }

        li = that._renderItemData( ul, item );

        if ( item.category ) {

          li.attr( "aria-label", item.category + " : " + item.label );

        }

      });

    }

  });



var items = [];

$(function() {

    var tmpdata = <?php echo $jsonitem;?>;



    data = $.map(tmpdata, function (item) {

            return {

                	label: item.itemName, 

                	category: item.category2+' '+item.category3,

                	value: item.componentId,

                	salePrice: item.saleprice,

                	quantity:1,

                	unit:item.unit

            };

        });

    $( "#findProduct" ).catcomplete({

      delay: 0,

      source: data,

      select: function( event, ui ) {

    	  addIt(ui.item);

      }

    });

  });



	Array.prototype.remove = function(from, to) {

	  var rest = this.slice((to || from) + 1 || this.length);

	  this.length = from < 0 ? this.length + from : from;

	  return this.push.apply(this, rest);

	};

	

	function deleteIt(i){

		items.remove(i);

        renderItem()

	}

	function addIt(item){

		 found = 0;

         for(i=0;i<items.length;i++){

       	  if(items[i].value == item.value){

           	  found = 1;

           	  items[i].quantity = items[i].quantity+1;;

       	  }

         }

         if(found==0)

   	  		items.push(item);

         renderItem();

	}

	

	function addItem(componentId, itemName,category2,category3, salePrice, unit){

		item = {'label': itemName, 

    	'category': category2+' '+category3,

    	'value': componentId,

    	'salePrice': salePrice,

    	'quantity':1,

    	'unit':unit};

		addIt(item);

	}

	function addQty(i,q){

		items[i].quantity = items[i].quantity+q;

		if(items[i].quantity ==0)

			deleteIt(i);

		else	

			renderItem();

	}



	crSize = <?php echo count($account);?>;

	receivable = <?php echo Applicationconst::ACCOUNT_HEAD_RECEIVABLE;?>;

	

	function renderItem(){

		$('.dynarow').remove();

		amt = 0.0;

		$("#productCounter").val(items.length);	

		

		for(i=0;i<items.length;i++){

			item = items[i];

			hdns="";

			amt += item.quantity * item.salePrice;

			

			hdns += "<input type=\"hidden\" name=\"itemId"+i+"\" value=\""+item.value+"\"/>";

			qty = "<input type=\"text\" onchange=\"updatequantity(this,"+i+");\" class=\"right\" name=\"quantity"+i+"\" value=\""+item.quantity+"\"/>";

			price = "<input type=\"text\" onchange=\"updateprice(this,"+i+");\" class=\"right\" name=\"unitPrice"+i+"\" value=\""+item.salePrice+"\"/>";

			del = "<i style=\"cursor:pointer;\" onclick=\"deleteIt("+i+");\" class=\"icon-trash\"></i>";

			plus = "<i style=\"cursor:pointer;\" onclick=\"addQty("+i+",1);\" class=\"icon-plus\"></i>";

			minus = "<i style=\"cursor:pointer;\" onclick=\"addQty("+i+",-1);\" class=\"icon-minus\"></i>";

			$('#productTable').append('<tr class=\"dynarow\" ><td>'+ hdns + item.label + '</td><td>' + price + '</td><td>'+qty+' '+item.unit+'&nbsp;&nbsp;&nbsp;' +minus+' '+plus+ '</td><td>'+del+'</td></tr>');

		}

		$('#amount').val(amt);



		crAmt = 0.0;

		revIdx = -1;

		for(i=1;i<=crSize;i++){

			caccId = $('#accountIdc'+i).val();

			if(caccId!=receivable){

				crAmt -= -$('#quantityc'+i).val();

			}else{

				revIdx = i;

			}

		}



		disc = $('#discount').val();

		$('#quantityc'+revIdx).val(amt - crAmt - disc);
		


	}



	function updateprice(obj, i){

		

		items[i].salePrice=parseFloat(obj.value);

		renderItem();

	}



	function updatequantity(obj, i){

		

		items[i].quantity=parseFloat(obj.value);

		renderItem();

	}



	function calculateDiscount(){

		renderItem();

	}

</script>