<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Surat Panggilan - <?= $surat->nomor_surat ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #000; padding-bottom: 20px; }
        .header h2 { margin: 5px 0; }
        .content { margin-top: 30px; line-height: 1.8; }
        .footer { margin-top: 50px; }
        .signature { float: right; text-align: center; margin-top: 50px; }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h2><?= $settings['nama_sekolah'] ?></h2>
        <p><?= $settings['alamat'] ?></p>
    </div>
    
    <div class="content">
        <p>Nomor: <?= $surat->nomor_surat ?></p>
        <p>Tanggal: <?= date('d F Y', strtotime($surat->tanggal_surat)) ?></p>
        
        <p>Kepada Yth,<br>Orang Tua/Wali dari:<br><strong><?= $surat->nama_lengkap ?></strong><br>
        Kelas: <?= $surat->tingkat ?> <?= $surat->nama_kelas ?></p>
        
        <p><strong>Perihal: <?= $surat->perihal ?></strong></p>
        
        <p>Dengan hormat,<br>Kami memohon kehadiran Bapak/Ibu untuk hadir di sekolah pada:</p>
        
        <p>Hari/Tanggal: <?= date('l, d F Y', strtotime($surat->waktu_panggilan)) ?><br>
        Waktu: <?= date('H:i', strtotime($surat->waktu_panggilan)) ?> WIB<br>
        Tempat: Ruang BK</p>
        
        <?php if ($surat->keterangan): ?>
        <p>Keterangan: <?= $surat->keterangan ?></p>
        <?php endif; ?>
        
        <p>Demikian surat panggilan ini kami sampaikan. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.</p>
    </div>
    
    <div class="signature">
        <p>Guru BK</p>
        <br><br><br>
        <p>_________________</p>
    </div>
</body>
</html>
