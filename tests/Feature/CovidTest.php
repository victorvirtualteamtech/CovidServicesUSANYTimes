<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CovidTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_covid()
    {
        // Test 0
        $response = $this->get('/api/covidLoadData/');
        $response->assertStatus(200);

        // Test 1
        $test_post = [
            "state" => "",
            'date' => "",
        ];
        $response = $this->post('/api/covid/', $test_post);
        $response->assertStatus(200);

        // Test 2
        $test_post = [
            "state" => "California",
            'date' => "",
        ];
        $response = $this->post('/api/covid/', $test_post);
        $response->assertStatus(200);        

        // Test 3
        $test_post = [
            "state" => "California",
            'date' => "2021-01-01",
        ];
        $response = $this->post('/api/covid/', $test_post);
        $response->assertStatus(200);

        // Test 4        
        $test_post = [
            "state" => "",
            'date' => "2021-01-01",
        ];
        $response = $this->post('/api/covid/', $test_post);
        $response->assertStatus(200);

        // Test 5
        $response = $this->get('/api/covidStates/');
        $response->assertStatus(200);        
        

    }
}
