<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserIteractionService;

class UserIteractionController extends Controller{

    private $userIteractionService;

    public function __construct(UserIteractionService $userIteractionService)
    {
        $this->userIteractionService = $userIteractionService;
    }
    
    public function react(Request $request){
        $data = $this->userIteractionService->react($request->all());
        return $this->responseSuccess($data,200);
        
    }
}
