<body>
	<div id="header">
		<div id="header-container">
			<img id="logo" src="<?php echo base_url(); ?>assets/images/logo.png" height="55px" />
			<div id="title">
				<span>DITJEN SUMBER DAYA DAN PERANGKAT POS DAN INFORMATIKA</span>
				<span>BALAI BESAR PENGUJIAN PERANGKAT TELEKOMUNIKASI</span>	
			</div>
			<?php
    				if($this->session->userdata('logged_in') == TRUE) {	
    		?>
    			<div>
					<ul id="menu">
						<li><a href="<?php	echo base_url(); ?>">Home</a></li>
						<li><a href="<?php	echo base_url(); ?>user">1. Administration</a></li>
						<li><a href="<?php	echo base_url(); ?>master">2. Master</a></li>
						<li><a href="<?php	echo base_url(); ?>report">3. Report</a></li>
						<li><a href="<?php	echo base_url(); ?>logout">Logout</a></li>						
					</ul>
				</div>
			<?php
				}else{
    		?>		
				<div>
					<ul id="menu">
						<li><a href="<?php	echo base_url(); ?>">Home</a></li>
						<li><a href="<?php	echo base_url(); ?>user">1. Administration</a></li>
						<li><a href="<?php	echo base_url(); ?>master">2. Master</a></li>
						<li><a href="<?php	echo base_url(); ?>report">3. Report</a></li>
					</ul>
				</div>	
			<?php
				}
			?>	
		</div>	
	</div>