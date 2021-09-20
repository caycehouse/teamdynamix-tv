<?php

namespace Tests\Unit;

use App\Models\Ticket;
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
        // Resolved Tickets
        Ticket::factory()->create(['status' => 'Closed']);
        Ticket::factory()->create(['status' => 'Cancelled']);

        // Unresolved Tickets
        Ticket::factory()->create(['status' => 'Work-in-Progress']);
        Ticket::factory()->create(['status' => 'New']);
        Ticket::factory()->create(['status' => 'On Hold']);

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

    /**
     * Tests the resolved ticket scope.
     *
     * @return void
     */
    public function testGettingOnlyUnresolvedTickets()
    {
        // Resolved Tickets
        Ticket::factory()->create(['status' => 'Closed']);
        Ticket::factory()->create(['status' => 'Cancelled']);

        // Unresolved Tickets
        Ticket::factory()->create(['status' => 'Work-in-Progress']);
        Ticket::factory()->create(['status' => 'New']);
        Ticket::factory()->create(['status' => 'On Hold']);

        $isUnresolved = true;
        foreach (Ticket::unresolved()->get() as $ticket) {
            $isUnresolved = ('New' === $ticket->status || 'Work-in-Progress' === $ticket->status || 'On Hold' === $ticket->status);

            if (! $isUnresolved) {
                break;
            }
        }

        $this->assertTrue($isUnresolved);
        $this->assertEquals(Ticket::unresolved()->count(), 3);
    }
}
