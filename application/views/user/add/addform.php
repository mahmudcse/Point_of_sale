<script type="text/javascript">
function prepareAction(formObj, action){
	if(formObj==null){
		alert('Undefined form');
		return;
	}
	if('delete' == action.toLowerCase()){
		if( confirm("Are you sure?")){
			formObj.action = '<?php echo base_url('index.php/user/delete');?>';
		}else{
			return;
		}
	}
	else if('cancel' == action.toLowerCase()){
	
		formObj.action = '<?php echo base_url('index.php/home/dashboard');?>';
		
	}
	formObj.submit();
}
</script>
<div id="userinput" class="col-md-offset-5" style="margin-top: 3%">

	<?php echo form_open('user/save');?>
		<input type="hidden" name="id" value="<?php echo $userId;?>" />
		<input type="hidden" name="version" value="<?php echo $version;?>" />
		<input type="hidden" name="operation" value="Cancel" /> 
		<input type="submit" name="submitcancel" value="Cancel" onclick="prepareAction(this.form, 'Cancel');">
&nbsp;
<?php
if ($userId > 0) {
?><input type="button" name="submitdelete" value="Delete" onclick="prepareAction(this.form,'Delete');">
<?php
}
?>
&nbsp;<input type="submit" name="submitsave" value="Save" onclick="prepareAction('Save');"> &nbsp;<br />
		<br />

		<table>
			<tr>
				<td><label>Username(*):</label></td>
				<td><input type="text" name="userName" value="<?php echo $userName;?>"></td>
			</tr>
			<tr>
				<td><label>First name:</label></td>
				<td><input type="text" name="firstName" value="<?php echo $firstName;?>"></td>
			</tr>
			<tr>
				<td><label>Last Name:</label></td>
				<td><input type="text" name="lastName" value="<?php echo $lastName;?>"></td>
			</tr>
			<tr>
				<td><label>Password(*):</label></td>
				<td><input type="password" name="password" value=""></td>
			</tr>
			<tr>
				<td><label>Confirm Password(*):</label></td>
				<td><input type="password" name="confirmPassword" value=""></td>
			</tr>
			<tr>
				<td><label>Email Address:</label></td>
				<td><input type="text" name="email" value="<?php echo $email;?>"></td>
			</tr>
		</table>

		<br /> <input type="submit" name="submitcancel" value="Cancel" onclick="prepareAction(this.form, 'Cancel');">
&nbsp;
<?php
if ($userId > 0) {
	?>
	<input type="button" name="submitdelete" value="Delete" onclick="prepareAction(this.form, 'Delete');">
<?php 
}
?>
&nbsp;<input type="submit" name="submitsave" value="Save" onclick="prepareAction('Save');">
<?php echo form_close();?>
	<br />
	<hr />

</div>