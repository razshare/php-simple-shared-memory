<?php
require_once './vendor/autoload.php';
use com\github\tncrazvan\simplesharedmemory\SimpleSharedMemory;


$mem = new SimpleSharedMemory();

$mem->persist("\n\nhello world\n\n");

print_r($mem->find());

$mem->delete();