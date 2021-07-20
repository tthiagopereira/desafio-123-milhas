<?php


namespace App\Services;
use Illuminate\Support\Facades\Http;

class VoosService
{
    private $groupService;
    public function __construct(VoosGroupService $groupService)
    {
        $this->groupService = $groupService;
    }

    public function index()
    {
        $res = Http::get('http://prova.123milhas.net/api/flights');
        $voos = $res->json();
        return $voos;
    }

    public function groupgoing()
    {
        $itens = $this->index();
        $voos = [];
        foreach ($itens as $item) {
            if($item['outbound'] === 1) {
                array_push($voos, $item);
            }
        }
        return $voos;
    }

    public function groupReturn()
    {
        $itens = $this->index();
        $voos = [];
        foreach ($itens as $item) {
            if($item['inbound'] === 1) {
                array_push($voos, $item);
            }
        }
        return $voos;
    }

    public function fare()
    {
        $itens = $this->index();
        $fate = [];
        foreach ($itens as $item){
            array_push($fate, $item['fare']);
        }
        $fare = array_unique($fate);
        $fareArray = [];
        foreach ($fare as $item) {
            array_push($fareArray, $item);
        }
        return $fareArray;
    }

    public function result()
    {
        $vool = [];
        $total = 1;
        $groupGoing = $this->groupService->group($this->groupgoing());
        $groupReturn = $this->groupService->group($this->groupReturn());
        $voosUnic = 0;
        $cheapestId = 0;

        for ($i=0;$i<count($groupGoing);$i++) {
            for ($j = 0; $j < count($groupReturn); $j++) {

                if($i ==0 && $j== 0){
                    $cheapestPrice = ($groupGoing[$i]['price'] + $groupReturn[$j]['price']);
                    $cheapestId = 1;
                }

                if($groupGoing[$i]['fare'] == $groupReturn[$j]['fare']){

                    $inbound = $this->groupService->groupVoos($groupReturn[$j]['price'], $groupReturn[$j]['fare'], $this->index(), 0,1);
                    $outbound = $this->groupService->groupVoos($groupGoing[$i]['price'], $groupGoing[$i]['fare'], $this->index(), 1,0);

                    if(count($inbound) == 1 && count($outbound) == 1 ) {
                        $voosUnic++;
                    }

                    if($cheapestPrice > ($groupGoing[$i]['price'] + $groupReturn[$j]['price'])) {
                        $cheapestPrice =  $groupGoing[$i]['price'] + $groupReturn[$j]['price'];
                        $cheapestId = $total;
                    }

                    array_push($vool, [
                        'groups' => [
                            'uniqueId' => $total++,
                            'fare' => $groupReturn[$j]['fare'],
                            'totalPrice'=> ($groupGoing[$i]['price'] + $groupReturn[$j]['price']),
                            'outbound' => $outbound,
                            'inbound' => $inbound,
                        ]
                    ]);
                }
            }
        }
        $vool[] = [
            'totalGroups' => $total - 1,
            'totalFlights' => $voosUnic,
            'cheapestPrice' => $cheapestPrice,
            'cheapestGroup' => $cheapestId
        ];
        array_unshift($vool, [
            'flights' => $this->index()
        ]);
        return $vool;
    }
}
