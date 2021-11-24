<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_add_tasks_to_projects()
    {
        $project = Project::factory()->create();

        $this->post($project->path().'/tasks')->assertRedirect('login');
    }

     /** @test */
     public function only_the_owner_of_project_may_add_tasks()
     {
        $this->signIn();

        $project = Project::factory()->create();

        $this->post($project->path().'/tasks', ['body' => 'Test task'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'Test task']);
     }

    /** @test */
    public function a_project_can_have_tasks()
    {  
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->post($project->path().'/tasks', ['body' => 'Test task']);

        $this->get($project->path())
                ->assertSee('Test task');
    }

    /** @test */
    public function a_task_requires_body()
    {
        $project = ProjectFactory::create();

        $attributes = Task::factory()->raw(['body' => '']);

        $this->actingAs($project->owner)
            ->post($project->path().'/tasks', $attributes)->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_task_can_be_update()
    {
        $project = ProjectFactory::withTasks(1)->create();

        $attributes = ['body' => 'Changed', 'completed' => true];

        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(), $attributes);

        $this->assertDatabaseHas('tasks', $attributes);
    }

     /** @test */
     public function only_the_owner_of_project_may_update_tasks()
     {
        $this->signIn();

        $project = ProjectFactory::withTasks(1)->create();

        $attributes = [
            'body' => 'Changed', 
            'completed' => true
        ];

        $this->patch($project->tasks[0]->path(), $attributes)
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', $attributes);
     }

}
