<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_homepage_requires_authentication(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }
}
