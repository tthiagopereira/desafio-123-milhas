<?php

namespace App\Http\Controllers;

use App\Services\VoosService;

class VoosController extends Controller
{

    private $service;
    public function __construct(VoosService $voosService)
    {
        $this->service = $voosService;
    }

    public function index()
    {
        $result = $this->service->result();
        return response()->json($result,200);
    }

    public function groupgoing()
    {
        return response()->json($this->service->groupgoing(), 200);
    }

    public function groupReturn()
    {
        return response()->json($this->service->groupReturn(), 200);
    }

    public function fare()
    {
        return response()->json($this->service->fare());
    }

}
