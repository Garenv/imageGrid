<?php

namespace Tests\Feature;

use Tests\TestCase;

class FaqTest extends TestCase
{
    public function test_get_faq_route()
    {
        $this->get("/");

        $response = $this->get('/faq');

        $response->assertStatus(200);
        $response->assertViewIs('faq');
        $response->assertViewHas('faq');
    }
}
