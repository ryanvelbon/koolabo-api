<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Meetup;
use App\Models\Comment;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_meetup_has_many_comments()
    {
        $this->seed();
        $meetup = Meetup::factory()->create();
        $n = 5;
        Comment::factory($n)->for($meetup, 'resource')->create();
        $meetup->refresh();
        $this->assertEquals($n, $meetup->comments->count());
    }

    /*
     * Comment has many comments.
     */
    public function test_comment_has_replies()
    {
        $this->seed();
        $meetup = Meetup::factory()->create();
        $comment = Comment::factory()->for($meetup, 'resource')->create();
        $n = 5; // number of replies to comment
        Comment::factory($n)->for($comment, 'resource')->create();
        $this->assertEquals(1, $meetup->refresh()->comments->count());
        $this->assertEquals($n, $comment->refresh()->replies->count());
    }
}
