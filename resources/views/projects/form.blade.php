<input type="text" name="title" placeholder="Input title" value="{{$project->title}}"> 
<textarea name="description" cols="30" rows="10" placeholder="input textarea">{{$project->description}}</textarea>
<button type="submit">{{$buttonText}}</button>
<a href="{{$project->path()}}">Отмена</a>

@if($errors->any())
    <div class="field mt-6">
        <ul>
            @foreach ($errors->all() as $error)
                <li class="text-sm text-red-500">{{$error}}</li>
            @endforeach
        </ul>
   
    </div>

@endif