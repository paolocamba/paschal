<?php
require '../vendor/autoload.php';

$logo_path = 'C:/xampp/htdocs/paschal/dist/assets/images/pmpclogo.jpg';

if (file_exists($logo_path)) {
    $logo_data = base64_encode(file_get_contents($logo_path));
    $logo_src = 'data:image/jpeg;base64,' . $logo_data; // Correct MIME type for JPG
} else {
    $logo_src = ''; // fallback
}

$html = '
    <div style="text-align: center;">
        <img src="' . $logo_src . '" style="width: 150px; margin-bottom: 20px;">
        <h1>Hello World</h1>
    </div>
';

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output();
