<div id="header-container">
	</br>
		<ul id="sidebar">
				<li><a href="<?php	echo base_url();?>user/userAdministration/alokasiAnggaran">Set Alokasi Anggaran</a></li>			
				<li><a href="<?php	echo base_url();?>user/userAdministration/notaSurat">Set Nota Surat</a></li>
				<li><a href="<?php	echo base_url();?>user/userAdministration/notaSuratExtra">Set Nota Surat Lain</a></li>
				<li><a href="<?php	echo base_url();?>user/userAdministration/subdit">Set Bagian/Bidang</a></li>
				<?php if ($this->session->userdata['role'] == 'Administrator'){?>
				<li><a href="<?php	echo base_url();?>user/userAdministration/users">Set User</a></li>
				<li><a href="<?php	echo base_url();?>user/userAdministration/no_tanggal_alokasiAnggaran">Set Nomor Anggaran</a></li>
				<?php } ?>
		</ul>
	</div>	
	</br></br></br></br>
		<?php foreach ($css_files as $file):?>
			<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
		<?php endforeach; ?>			
		<?php foreach ($js_files as $file): ?>			
			<script src="<?php echo $file; ?>"></script>			
		<?php endforeach; ?>	
	</br>
<div id="container">
	<div id="content-area">	
		<?php echo $output; ?>
		<?php 	/*$user_data = $this->session->userdata('username');
				echo $user_data;*/
		?>
	</div>
</div>