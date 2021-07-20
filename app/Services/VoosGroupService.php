<?php


namespace App\Services;


class VoosGroupService
{
    public function group($array)
    {
        $voos = [];
        for($i=0;$i<count($array); $i++) {
            if($i==0) {
                array_push($voos, $array[$i]);
            }
            if($i > 0) {
                if($array[$i]['price'] != $array[($i-1)]['price']) {
                    array_push($voos, $array[$i]);
                }
            }
        }
        return $voos;
    }

    public function groupVoos($price, $fare, $array, $outbound, $inbound)
    {
        $voosId = [];
        foreach ($array as $item) {
            if($price == $item['price'] && $fare == $item['fare'] && $item['outbound'] == $outbound) {
                array_push($voosId, $item['id']);
            }
        }
        return $voosId;
    }
}
