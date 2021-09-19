<?php

namespace Database\Factories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition()
    {
        /*
        Using rand(1,50) instead of User::inRandomOrder()->first().
        More efficient but of course not 100% reliable. Will only
        work insofar as there are indeed users for IDs 1 to 50.

        Also note that a Comment needs to point to a resource.

        Therefore, the following will not work:
        
          Comment::factory()->create()

        You must specify the resource:

          Comment::factory()->for($meetup, 'resource')->create()

        */
        return [
            'author_id' => rand(1,50), // *REVISE*
            'body' => $this->faker->sentence()
        ];
    }
}
