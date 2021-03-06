<?php

namespace Tests\Feature;

use App\Models\Task;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use function PHPUnit\Framework\assertCount;

class TriggerActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test*/
    public function creating_a_project()
    {
        $project = ProjectFactory::create();

        $this->assertCount(1, $project->activity);

        $this->assertEquals('created', $project->activity[0]->description);
    } 
    
    /** @test*/
    public function updating_a_project()
    {
        $project = ProjectFactory::create();
    
        $project->update(['title' => 'changed']);

        $this->assertCount(2, $project->activity);
    
        $this->assertEquals('updated', $project->activity->last()->description);
    } 


    /** @test */
    public function creating_a_new_task()
    {

        $project = ProjectFactory::create();

        $project->addTask('new task');

        $this->assertCount(2, $project->activity);

        tap($project->activity->last(), function($activity){
            $this->assertEquals('created_task', $activity->description);
            $this->assertInstanceOf(Task::class, $activity->subject);
            $this->assertEquals('new task', $activity->subject->body);
        });
    }

    /** @test*/
    public function completing_a_task()
    {

        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(), [
                'body' => 'changed', 
                'completed' => true
            ]);

        $this->assertCount(3, $project->activity);

        $this->assertEquals('completed_task', $project->activity->last()->description);
    }

    /** @test*/
    public function incompleting_a_task()
    {
        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(), [
                'body' => 'changed', 
                'completed' => true
            ]);

        $this->assertCount(3, $project->activity);

        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(), [
                'body' => 'changed', 
                'completed' => false
            ]);
       
        $project->refresh();

        $this->assertCount(4, $project->activity);

        $this->assertEquals('incompleted_task', $project->activity->last()->description);
    }

     /** @test*/
     public function deleting_a_new_task()
     {
        $project = ProjectFactory::create();

        $task = $project->addTask('new task');

        $this->assertCount(2, $project->activity);

        $task->delete();

        $project->refresh();
        
        $this->assertCount(3, $project->activity);

        $this->assertEquals('deleted_task', $project->activity->last()->description);
     }
}
