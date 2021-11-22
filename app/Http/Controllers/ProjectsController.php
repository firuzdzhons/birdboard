<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Facade\FlareClient\View;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function index()
    {
        $projects = auth()->user()->projects;

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }


    public function store()
    {
        //validate
        $attributes = request()->validate([
            'title' => 'required', 
            'description' => 'required'
        ]);

        //persist
        auth()->user()->projects()->create($attributes);
        // Project::create($attributes);

        //redirect
        return redirect('/projects');
    }

    public function show(Project $project)
    {
        if(auth()->id() != $project->owner_id){
            abort(403);
        }

        return response()->view('projects.show', compact('project'));
    }
}
