<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResponseTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_root_response()
    {
        $response = $this->get(env("APP_URL"));

        $response->assertStatus(200);
    }

    public function test_moscow_response()
    {
        $response = $this->get("moscow." . env("APP_URL"));

        $response->assertStatus(200);
    }

    public function test_ajax_response()
    {
        $response = $this->get(env("APP_URL") . "ajax?sellbuy=all&offset=1");

        $response->assertStatus(200);
    }
}
