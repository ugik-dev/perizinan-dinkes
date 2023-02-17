

<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


if (!function_exists('status_ujian')) {
    //USED TO FETCH AND COUNT THE NUMBER OF OCCURANCE IN RETURN STOCK
    function status_ujian($p)
    {
        if ($p == "N")
            return "Tidak Lulus!";
        else  if ($p == "Y")
            return "Lulus!";
        else
            return "Menunggu!";
    }
}

if (!function_exists('tanggal_indonesia')) {
    function tanggal_indonesia($tanggal, $time = false)
    {
        $pukul = "";
        if ($time) {
            $t = explode(' ', $tanggal);
            if (!empty($t[1])) $pukul = " pukul " . substr($t[1], 0, 5) . " WIB";
        }
        if (empty($tanggal)) return '';
        $BULAN = [
            0, 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        $t = explode('-', $tanggal);
        $tg = intval($t[2]);
        return "{$tg} {$BULAN[intval($t[1])]} {$t[0]}{$pukul}";
    }
}
