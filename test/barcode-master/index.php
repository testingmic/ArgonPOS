<?php 
include 'barcode.php';

$generator = new barcode_generator();

$array = [
		'PRA12345', 'PRA28883',
		'PRA29939', 'PRAL49445'
];

foreach($array as $data) {
	print $generator->output_image('png', 'ean-128', $data, [
		'sf' => 2, 'pl' => 0, 'pr' => 0
	]);
}
?>