<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserIteractionService;
use Validator;

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

    public function search(Request $request){
        $data = $this->userIteractionService->search($request->all());
        return $this->responseSuccess($data,200);
        
    }
}
