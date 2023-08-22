<!DOCTYPE html>
<html>
<head>
	<title>Membuat Pagination Pada CodeIgniter | MalasNgoding.com</title>
</head>
<body>
<h1>Membuat Pagination Pada CodeIgniter | MalasNgoding.com</h1>
	<table border="1">
		<tr>
			<th>no</th>
			<th>nama</th>
			<th>alamat</th>
			<th>pekerjaan</th>		
		</tr>
		<?php 
		$no=1;
		foreach($user as $u){ 
		?>
		<tr>
			<td><?php echo $no++; ?></td>
			<td><?php echo $u->nik ?></td>
			<td><?php echo $u->nik ?></td>
			<td><?php echo $u->nik ?></td>
		</tr>
	<?php } ?>
	</table>
	<br/>
	<?php 
	echo $this->pagination->create_links();
	?>
</body>
</html>