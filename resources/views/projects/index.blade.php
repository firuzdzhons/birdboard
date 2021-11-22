<x-app-layout>

    <header class="flex items-center mb-3 py-4">
        <div class="flex justify-between items-center w-full">
            <h2 class="text-gray-500 text-sm font-normal">My Projects</h2>

            <a href="/projects/create" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">New Project</a>
        </div>
    </header>
    
    <div class="lg:flex lg:flex-wrap -mx-3">
        @forelse ($projects as $project)
        <div class="lg:w-1/3 px-3 pb-6">
           @include('projects.card')
        </div>
           
        @empty    
            <div>No projects yet.</div>
        @endforelse
    </div>
</x-app-layout>