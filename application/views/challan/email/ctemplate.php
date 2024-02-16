<h4>Dear Customer,</h4>

<p>Your challan is given below.</p>
<?php $this->load->view('challan/add/challan');?>

<h3> <a href="<?php echo base_url('index.php/challan/downloadchallan/'.$downloadid);?>">Click here to download the challan in PDF.</a></h3>
<p>Regards,</p>