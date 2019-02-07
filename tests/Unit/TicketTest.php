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

    /**
     * Tests the resolved ticket scope.
     *
     * @return void
     */
    public function testGettingOnlyUnresolvedTickets()
    {
        $faker = Factory::create();

        // Resolved Tickets
        factory(Ticket::class)->create(['status' => 'Closed']);
        factory(Ticket::class)->create(['status' => 'Cancelled']);

        // Unresolved Tickets
        factory(Ticket::class)->create(['status' => 'Work-in-Progress']);
        factory(Ticket::class)->create(['status' => 'New']);
        factory(Ticket::class)->create(['status' => 'On Hold']);

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
