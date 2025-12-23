<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Panggilan - <?= $surat['nomor_surat'] ?></title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; margin: 20px; }
        .kop { text-align: center; border-bottom: 3px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .kop h2 { margin: 5px 0; font-size: 18pt; }
        .kop p { margin: 3px 0; font-size: 11pt; }
        .content { margin: 30px 50px; }
        .nomor { text-align: right; margin-bottom: 20px; }
        .perihal { margin-bottom: 30px; }
        .isi { text-align: justify; line-height: 1.8; }
        .ttd { margin-top: 50px; text-align: right; }
        .ttd-space { height: 80px; }
        table { width: 100%; }
        @media print {
            @page { size: A4; margin: 15mm; }
            body { margin: 0; }
        }
    </style>
</head>
<body>

    <!-- Kop Surat -->
    <div class="kop">
        <h2><?= strtoupper($settings['nama_sekolah']) ?></h2>
        <p><?= $settings['alamat'] ?></p>
        <p>Telp: <?= $settings['telepon'] ?? '-' ?> | Email: <?= $settings['email'] ?? '-' ?></p>
    </div>

    <div class="content">
        <!-- Nomor Surat -->
        <div class="nomor">
            <p>
                Nomor : <?= $surat['nomor_surat'] ?><br>
                Hal : Panggilan Orang Tua/Wali
            </p>
        </div>

        <!-- Kepada -->
        <p>Kepada Yth.<br>
        <strong>Bapak/Ibu Orang Tua/Wali<br>
        <?= $surat['nama_siswa'] ?> (<?= $surat['nama_kelas'] ?>)</strong><br>
        Di Tempat</p>

        <!-- Salam -->
        <p style="margin-top: 20px;">Assalamu'alaikum Wr. Wb.</p>
        <p style="text-indent: 30px;">Dengan hormat,</p>

        <!-- Isi Surat -->
        <div class="isi">
            <p style="text-indent: 30px;">
                Sehubungan dengan <?= $surat['perihal'] ?>, 
                dengan ini kami mengundang Bapak/Ibu untuk hadir di sekolah kami pada:
            </p>

            <table style="width: 80%; margin: 20px auto;">
                <tr>
                    <td width="25%">Hari, Tanggal</td>
                    <td width="5%">:</td>
                    <td><strong><?= $surat['hari'] ?>, <?= date('d F Y', strtotime($surat['tanggal'])) ?></strong></td>
                </tr>
                <tr>
                    <td>Waktu</td>
                    <td>:</td>
                    <td><strong><?= $surat['waktu'] ?> WIB</strong></td>
                </tr>
                <tr>
                    <td>Tempat</td>
                    <td>:</td>
                    <td><strong>Ruang BK <?= $settings['nama_sekolah'] ?></strong></td>
                </tr>
            </table>

            <p style="text-indent: 30px;">
                Demikian surat panggilan ini kami sampaikan. Atas perhatian dan kerjasamanya kami ucapkan terima kasih.
            </p>

            <p>Wassalamu'alaikum Wr. Wb.</p>
        </div>

        <!-- Tanda Tangan -->
        <div class="ttd">
            <p><?= date('d F Y', strtotime($surat['tanggal'])) ?></p>
            <p>Guru BK,</p>
            <div class="ttd-space"></div>
            <p><strong><u><?= $surat['nama_bk'] ?></u></strong></p>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>

</body>
</html>
