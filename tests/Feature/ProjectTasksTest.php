<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
        $this->signIn();

        $project = auth()->user()->projects()->create(
            Project::factory()->raw()
        );

        $this->post($project->path().'/tasks', ['body' => 'Test task']);

        $this->get($project->path())
                ->assertSee('Test task');
    }

    /** @test */
    public function a_task_requires_body()
    {
        $this->signIn();

        $project = auth()->user()->projects()->create(
            Project::factory()->raw()
        );

        $attributes = Task::factory()->raw(['body' => '']);

        $this->post($project->path().'/tasks', $attributes)->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_task_can_be_update()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $project = auth()->user()->projects()->create(
            Project::factory()->raw()
        );

        $task = $project->addTask('Test task');

        $attributes = ['body' => 'Changed', 'completed' => true];

        $this->patch($task->path(), $attributes);

        $this->assertDatabaseHas('tasks', $attributes);
    }

     /** @test */
     public function only_the_owner_of_project_may_update_tasks()
     {
        $this->signIn();

        $project = Project::factory()->create();

        $task = $project->addTask('Test task');

        $attributes = [
            'body' => 'Changed', 
            'completed' => true
        ];

        $this->patch($task->path(), $attributes)
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', $attributes);
     }

}
