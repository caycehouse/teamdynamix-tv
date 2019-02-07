<?php

namespace Tests\Feature;

use Tests\TestCase;

class DashboardTest extends TestCase
{
    /**
     * A user can visit the dashboard.
     *
     * @return void
     */
    public function testDashboard()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
