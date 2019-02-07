<?php

namespace Tests\Unit;

use App\Ticket;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Tests the resolved ticket scope.
     *
     * @return void
     */
    public function testGettingOnlyResolvedTickets()
    {
        $faker = Factory::create();

        // Resolved Tickets
        factory(Ticket::class)->create(['status' => 'Closed']);
        factory(Ticket::class)->create(['status' => 'Cancelled']);

        // Unresolved Tickets
        factory(Ticket::class)->create(['status' => 'Work-in-Progress']);
        factory(Ticket::class)->create(['status' => 'New']);
        factory(Ticket::class)->create(['status' => 'On Hold']);

        $isResolved = true;
        foreach (Ticket::resolved()->get() as $ticket) {
            $isResolved = ('Closed' === $ticket->status || 'Cancelled' === $ticket->status);

            if (! $isResolved) {
                break;
            }
        }

        $this->assertTrue($isResolved);
        $this->assertEquals(Ticket::resolved()->count(), 2);
    }
}
