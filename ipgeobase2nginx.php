<?php
function addressesToCidr($address)
{
    list($min, $max) = explode('-', $address);
    $min    = trim($min);
    $minDec = ip2long($min);
    $minBin = decbin($minDec);
    $pos    = strpos(strrev($minBin), '1', 0);
    $net    = 32 - $pos;

    $cidr = "$min/$net";

    return $cidr;
}

$file_to_update = './geo_ipgeobase.cfg';

$cities       = file_get_contents('cities.txt');
$cities       = mb_convert_encoding($cities, 'utf8', 'cp1251');
$cities       = explode("\n", $cities);
$count_cities = count($cities);

$addrs = file_get_contents('cidr_optim.txt');
$addrs = mb_convert_encoding($addrs, 'utf8', 'cp1251');
$addrs = explode("\n", $addrs);

$count = count($addrs);
$data  = "";
$fp    = fopen($file_to_update, "w");
for ($i = 0; $i < $count; $i++) {
    if (empty($addrs[$i])) {
        continue;
    }

    $addrs_row = explode("\t", $addrs[$i]);
    $cidr      = addressesToCidr($addrs_row[2]);
    $country   = $addrs_row[3];

    $data = "$cidr $country;\n";
    fwrite($fp, $data);
}
