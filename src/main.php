<?php
require_once './vendor/autoload.php';

$mem = new SimpleSharedMemory();

$mem->persist("\n\nhello world\n\n");

print_r($mem->find());

$mem->delete();