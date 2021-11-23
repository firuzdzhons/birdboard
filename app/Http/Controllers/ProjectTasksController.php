<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class ProjectTasksController extends Controller
{
    public function store(Project $project)
    {
        request()->validate([
            'body' => 'required'
        ]);

        if(auth()->id() != $project->owner_id)
            abort(403);
            
        $project->addTask(request('body'));
        
        return redirect($project->path());
    }

    public function update(Project $project, Task $task)
    {
        if(auth()->id() != $project->owner_id)
            abort(403);

        $task->update([
            'body' => request('body'),
            'completed' => request()->has('completed')
        ]);

        return redirect($project->path());
    }
}
