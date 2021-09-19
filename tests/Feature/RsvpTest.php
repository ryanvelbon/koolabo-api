<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Meetup;
use App\Models\Rsvp;

class RsvpTest extends TestCase
{
    public function test_meetup_organizer_can_invite()
    {
        $user = User::factory()->create();
        $meetup = Meetup::factory()->for($user, 'organizer')->create();
        $invitees = User::factory()->create();

        Sanctum::actingAs($user, ['*']);
        $data = [
            // 'invitee_id' = 
        ];
        $this->json('POST', "/api/meetups/{$meetup->id}/rsvps", $data);
        
    }

    public function test_meetup_organizer_can_cancel_invite()
    {
        $this->assertsTrue(False);
    }

    public function test_invitee_can_decline_invitation()
    {
        $this->assertsTrue(False);
    }

    public function test_invitee_can_accept_invitation()
    {
        $this->assertsTrue(False);
    }

    /*
     * After meetup is over, user should be able to update
     * RSVP status to 'attended' or 'did not attend'
     */
    public function test_invitee_is_asked_if_they_attended()
    {
        $this->assertsTrue(False);
    }

    /*
     * Some loose code which needs to be organized into test functions
     *
     */
    public function SKIPtest_x()
    {
        // $meetup = Meetup::factory()->has(
        //     Attendee::factory(20)->create();
        // )->create();

        User::factory(10)->create();
        $meetup = Meetup::factory()->create();

        RSVP::factory()->for($meetup)->create();

        RSVP::factory()->for($meetup)->create(['status' => RSVP::STATUS_ATTENDING]);
        RSVP::factory()->for($meetup)->create(['status' => RSVP::STATUS_MAYBE]);

        // $meetup->attendees()->factory(5)->create();
        // $this->assertEquals($n, $response->json('data')[0]['nAttendees']);

        $meetup->images()->create(['path' => 'image.png']);
        $meetup->refresh();
        $this->assertEquals(1, sizeof($meetup->images));
    }
}
