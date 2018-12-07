<?php include 'footer.php';?>
            </div>
        </div>
        <?php include 'includes_bottom.php';?>
        <?php include 'modal_hidden.php';?>
         <iframe name="printframe" width="0" height="0" frameborder="0" src="about:blank"></iframe>
		<script type="text/javascript">
	            printDivCSS = new String ("<link rel=\"stylesheet\" href=\"<?php echo base_url();?>assets/css/printstyle.css\" type=\"text/css\">");
	         
	            function printIt(divId) {
	                window.frames["printframe"].document.body.innerHTML=printDivCSS + document.getElementById(divId).innerHTML;
	                window.frames["printframe"].window.focus();
	                window.frames["printframe"].window.print();
	            }
	 	</script>
	</body>
</html>