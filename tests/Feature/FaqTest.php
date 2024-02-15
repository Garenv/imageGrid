<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use const Grpc\STATUS_OK;

class FaqTest extends TestCase
{
    /**
     * Test the /get-faq route.
     *
     * @return void
     */

    protected function setUp(): void
    {
        parent::setUp();

        // Disable email verification in the test environment
//        config(['auth.verification.enabled' => false]);
    }

    public function test_get_faq_route()
    {
        // Arrange: (Optional) Prepare any required data/models

        // Act: Make a GET request to the route
        $response = $this->get("/");

        $response = $this->get('/faq');
        // Assert: Check if the response status is 200 (OK)

        $response->assertStatus(200);

        // Assert: Check if the view is the expected one (faq.blade.php)
        $response->assertViewIs('faq');
//
        // Assert: Check if the view has the correct data
        // Assuming your data is passed as 'faqs' to the view
        $response->assertViewHas('faqs');
    }
}
