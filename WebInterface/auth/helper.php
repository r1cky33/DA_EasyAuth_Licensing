<?php
function get_file_hex($fname){
    $filearray = file($fname);

    $ret = "";
    for($i = 0; $i < count($filearray); $i++) {
        $r = bin2hex("$filearray[$i] ");
        $res = substr($r, 0, -2);
        $ret .= $res;
    }
    return $ret;
}

function stringToArray($s)
{
    $r = array();
    for($i=0; $i<strlen($s); $i++)
        $r[$i] = $s[$i];
    return $r;
}

function xorEncrypt( $InputString, $KeyPhrase )
{
    $res="";

    $vlen = strlen($InputString);
    $klen = strlen($KeyPhrase);
    $v = 0;
    $k = 0;

    for ($v=0; $v < $vlen; $v++) {
        $c = $InputString[$v] ^ $KeyPhrase[$k];
        $res=$res.$c;
        //$k = (++$k < $klen ? $k : 0);

        if(($k + 1) < $klen){
            $k = $k + 1;
        }
        else{
            $k = 0;
        }
    }

    return $res;
}
?>
