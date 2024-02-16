
<div class="container">
	<div class="row top-buffer-narrow">
		<div class="col-md-12">
			<input class="btn btn-default" type="button" name="submitcancel" value="Cancel" onclick="prepareAction(this.form,'cancel');"/>
			<?php if (strpos($page_title, 'Add') === false) { ?>
			<input class="btn btn-default" type="button" name="submitdelete" value="Delete" onclick="prepareAction(this.form,'delete');"/>
			<input class="btn btn-default" type="button" name="submitmodify" value="Modify" onclick="prepareAction(this.form,'update');"/>
			<?php }else{ ?>
			<input class="btn btn-default" type="button" name="submitsave" value="Save" onclick="prepareAction(this.form,'save');"> &nbsp;<br />
			<?php }?>
		</div>
	</div>
</div>