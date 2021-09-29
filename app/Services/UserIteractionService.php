<?php

namespace App\Services;

use App\Repositories\UserIteractionRepository;

class UserIteractionService
{
  private $userIteractionRepository;

  public function __construct(UserIteractionRepository $userIteractionRepository)
  {
      $this->userIteractionRepository = $userIteractionRepository;
  }

  public function react($data){
    return $this->userIteractionRepository->react($data);
  }

}

