<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Image;
use App\Models\User;
use App\Models\Project;
use App\Models\Meetup;

class ImageTest extends TestCase
{
    use RefreshDatabase;

    /*
     * A single table is used to contain images for users, projects and meetups. 
     */
    public function test_polymorphic()
    {
        $this->seed();

        $nImages_original = Image::count();

        $a = 4;
        $b = 5;
        $c = 2;

        $user = User::factory()->create();
        $project = Project::factory()->create();
        $meetup = Meetup::factory()->create();


        for ($i=0; $i <$a ; $i++) { 
            $user->images()->create(['path' => 'image.png']);
        }
        for ($i=0; $i <$b ; $i++) { 
            $project->images()->create(['path' => 'image.png']);
        }
        for ($i=0; $i <$c ; $i++) { 
            $meetup->images()->create(['path' => 'image.png']);
        }

        

        $nImages_now = $nImages_original + $a + $b + $c;

        $this->assertEquals($nImages_now, Image::count());
    }
}
