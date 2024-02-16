<h4>Dear Customer,</h4>

<p>Your invoice is given below.</p>
<?php $this->load->view('challan/add/invoice');?>

<h3> <a href="<?php echo base_url('index.php/challan/downloadinvoice/'.$downloadid);?>">Click here to download the invoice in PDF.</a></h3>
<p>Regards,</p>