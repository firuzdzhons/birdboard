<?php

namespace App\Http\Controllers;

use App\Models\Project;
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
}
