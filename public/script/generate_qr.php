<?php
require_once '../../vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

$text = $_GET['text'] ?? 'Capdrake est magnifique';
$qrCode = new QrCode($text);
$qrCode->setSize(600);
$writer = new PngWriter();

header('Content-Type: image/png');
echo $writer->write($qrCode)->getString();
