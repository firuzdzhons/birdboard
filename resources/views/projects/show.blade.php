<x-app-layout>
    <header class="flex items-center mb-3 py-4">
        <div class="flex justify-between items-center w-full">
            <h2 class="text-gray-500 text-sm font-normal">
                <a href="/projects" class="text-gray-500 text-sm font-normal">My Projects</a> / {{$project->title}}
            
            </h2>

            <a href="/projects/create" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">New Project</a>
        </div>
    </header>
    
    <main>
        <div class="lg:flex -mx-3">
            <div class="lg:w-3/4 px-3 mb-6">
                <div class="mb-6">
                    <h2 class="text-lg text-gray-500 font-normal mb-3">Tasks</h2>                   
                    @foreach($project->tasks as $task)
                        <div class="bg-white p-5 shadow rounded-lg mb-3">
                            <form method="POST" action="{{$task->path()}}">
                                @csrf
                                @method('PATCH')

                                <div class="flex align-items-center">
                                    <input type="text" name="body" id="" value="{{$task->body}}" class="w-full {{$task->completed ? 'text-grey-500' : ''}}">
                                    <input type="checkbox" name="completed" {{$task->completed ? 'checked' : ''}} id="" onChange="this.form.submit()">
                                </div>
                            </form>
                           
                        </div>
                    @endforeach
                    <div class="bg-white p-5 shadow rounded-lg">
                           <form action="{{$project->path()}}/tasks" method="POST">
                               @csrf
                               <input class="w-full" type="text" name="body" id="" placeholder="Add new task...">
                           </form>
                    </div>
                </div>

                <div>
                    <h2 class="text-lg text-gray-500 font-normal mb-3">General Notes</h2>
                    <form method="POST" action="{{$project->path()}}">
                        @csrf
                        @method('PATCH')
                        <textarea name="notes" id="" class="flex align-items-center w-full" style="min-height: 200px">
                            {{$project->notes}}
                        </textarea>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Save</button>
                    </form>
                </div>
            </div>
         

            <div class="lg:w-1/4 px-3">
                @include('projects.card')
            </div>
        </div>
    </main>
</x-app-layout>