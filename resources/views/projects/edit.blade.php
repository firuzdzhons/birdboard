<x-app-layout>
    <h1>Edit project</h1>
    <form method="POST" action="{{$project->path()}}">
        @csrf
        @method('PATCH')
        @include('projects.form', ['buttonText' => 'edit'])
    </form>
</x-app-layout>