<?php 
session_start();
error_reporting(0);
include "../../config/databases.php";

if (isset($_GET['ta']) && isset($_GET['tp'])) {
    $periode_id = mysqli_real_escape_string($con, $_GET['ta']); // Menangani input untuk mencegah SQL injection
    $th_periode = mysqli_real_escape_string($con, $_GET['tp']); // Menangani input untuk mencegah SQL injection
    
    $daftarPembing = mysqli_query($con, "SELECT 
    	tb_mhs.nim,
    	tb_mhs.nama,
    	tm_prodi.prodi,
    	tm_prodi.konsen,
    	tb_dsn.nama_dosen,
    	pengajuan.judul
    	FROM pembing
    	JOIN tb_mhs ON pembing.id_mhs = tb_mhs.id_mhs
    	JOIN tm_prodi ON tb_mhs.prodi_id = tm_prodi.prodi_id
    	JOIN pengajuan ON pembing.pengajuan_id = pengajuan.pengajuan_id
    	JOIN tb_dsn ON pembing.dosen = tb_dsn.id_dsn
    	WHERE pembing.kesediaan = 'Y'
    	AND pembing.periode = '$periode_id' AND pengajuan.status_sk = '$th_periode'
    	ORDER BY pembing.pembing_id ASC");

    if (mysqli_num_rows($daftarPembing) > 0) {
    	?>
    	<!DOCTYPE html>
    	<html lang="en">
    	<head>
    		<meta charset="UTF-8">
    		<title>Print Table</title>
    		<!-- Include CSS styles for printing -->
    		<style>
    			@media print {
    				/* Define CSS styles for printing */
    				/* For example, hide certain elements not needed for printing */
    				.no-print {
    					display: none;
    				}
    			}
    		</style>
    	</head>
    	<body>
    		<!-- Tampilkan tabel di sini -->
    		<table width="100%" cellpadding="3" border="1" style="border-collapse: collapse;">
    			<tr>
    				<th>NO</th>
    				<th>NAMA</th>
    				<th>NIM</th>
    				<th>PROGRAM STUDI</th>
    				<th>KONSENTRASI</th>
    				<th>JUDUL</th>
    				<th>PEMBIMBING</th>
    			</tr>
    			<?php
    			$i = 1;
    			while ($d = mysqli_fetch_assoc($daftarPembing)) :
    				?>
    				<tr>
    					<td><?= $i++ ?>.</td>
    					<td><?= $d['nama'] ?></td>
    					<td><?= $d['nim'] ?></td>
    					<td><?= $d['prodi'] ?></td>
    					<td><?= $d['konsen'] ?></td>
    					<td><?= $d['judul'] ?></td>
    					<td><?= $d['nama_dosen'] ?></td>
    				</tr>
    			<?php endwhile; ?>
    		</table>

    		<!-- Button for printing -->
    		<button class="no-print" onclick="window.print()">Print</button>

    		<!-- Additional content or footer if needed -->

    	</body>
    	</html>
    	<?php
    } else {
    	echo "Tidak ada data untuk ditampilkan.";
    }
} else {
	echo "Data tidak lengkap.";
}
?>
