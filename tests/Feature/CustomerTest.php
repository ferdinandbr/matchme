<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerTest extends TestCase
{
   /** @test */
    public function only_loged_users_can_se_profile()
    {
       $response = $this->get('api/user-profile');

       $response->assertStatus(401);
       
    }
    
}
