<x-app-layout>
    <h1>New project</h1>
    <form method="POST" action="/projects">
        @csrf
        @include('projects.form', ['project' => new App\Models\Project, 'buttonText' => 'create'])
    </form>
</x-app-layout>