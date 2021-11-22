<x-app-layout>
    <form method="POST" action="/projects">
        @csrf
        <input type="text" name="title" placeholder="Input title"> 
        <textarea name="description" cols="30" rows="10" placeholder="input textarea"></textarea>
        <input type="submit" value="Save">
    </form>
</x-app-layout>