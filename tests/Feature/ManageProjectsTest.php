<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;

class ManageProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function guests_cannot_control_projects()
    {

        $project = Project::factory()->create();

        $this->post('/projects', $project->toArray())->assertRedirect('login');

        $this->get('/projects')->assertRedirect('login');

        $this->get('/projects/create')->assertRedirect('login');

        $this->get($project->path())->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_create_a_project()
    {
        $this->withoutExceptionHandling();     

        $this->signIn();
        
        $this->get('/projects/create')->assertStatus(200);

        $attributes = [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->sentence(),
            'notes' => 'General notes'
        ];

        $response = $this->post('/projects', $attributes);

        $project = Project::where($attributes)->first();

        $response->assertRedirect($project->path());

        $this->get($project->path())
            ->assertSee($attributes['title'])
            ->assertSee($attributes['description'])
            ->assertSee($attributes['notes']);
     }


        /** @test */
    public function a_user_can_update_a_project()
    {
        $this->withoutExceptionHandling();     

        $project = ProjectFactory::create();

        $this->actingAs($project->owner)->get($project->path().'/edit')->assertOk();

        $this->actingAs($project->owner)  
            ->patch($project->path(), $attributes = ['title' => 'changed', 'description' => 'changed', 'notes' => 'changed'])
            ->assertRedirect($project->path());
   
        $this->assertDatabaseHas('projects', $attributes);
     }

    
     /** @test */
     public function a_user_can_view_their_project()
     {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
     }

       /** @test */
     public function a_user_cannot_view_projects_of_others()
     {
        $this->signIn();

        $project = Project::factory()->create();

        $this->get($project->path())
            ->assertStatus(403);
     }

    /** @test */
    public function a_project_requires_title(){
        $this->signIn();

        $attributes = Project::factory()->raw(['title' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_project_requires_description()
    {
        $this->signIn();

        $attributes = Project::factory()->raw(['description' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }
}
