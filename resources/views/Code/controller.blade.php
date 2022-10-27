<!DOCTYPE html>
<html>
<head>
    <title>Apolable - Dynamically Controller MODEL and Migration Code Creation</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<div class="container">
    <h1>Model Name: {{ ucwords($model_name) }}</h1>

<table class="table table-bordered" id="dynamic_field">
{{--model and migration    --}}
<tr>
<td>
<h2>Model File</h2>
<textarea name="" id="" cols="40" rows="10">
use HasRoles;

protected $fillable = [
@foreach($items as $item)
'{{ str_replace(' ', '_', $item) }}',
@endforeach
];</textarea>
</td>

<td>
<h2>Migration File</h2>
<textarea name="" id="" cols="60" rows="10">
@foreach($items as $item)
$table->string('{{ str_replace(' ', '_', $item) }}')->nullable();
@endforeach
$table->string('status')->nullable()->default('inactive');
$table->softDeletes();
</textarea>
</td>
</tr>

{{--index and create    --}}
<tr>
<td>
<h4>Index File</h4>
<textarea name="" id="" cols="60" rows="10">
//Check authentication
if (!Auth::user()->can('{{$model_name}}.all')){
    abort(403,'Unauthorized Action');
}
$data['{{$model_name}}']= {{ucwords($model_name)}}::where('status','active')->orderBy('id','ASC')->get();
return view('{{ strtolower($location) }}.{{$model_name}}.index');
 </textarea>
</td>

<td>
<h4>Create File</h4>
<textarea name="" id="" cols="60" rows="10">
//Check authentication
if (!Auth::user()->can('{{$model_name}}.create')){
abort(403,'Unauthorized Action');
}
return view('{{ strtolower($location) }}.{{$model_name}}.create');
</textarea>
</td>
</tr>

{{-- show and edit    --}}
<tr>
<td>
<h4>Show File</h4>
<textarea name="" id="" cols="60" rows="10">
//Check authentication
if (!Auth::user()->can('{{$model_name}}.show')){
    abort(403,'Unauthorized Action');
}
$data['{{$model_name}}'] = {{ucwords($model_name)}}::findOrFail($id);
return view('{{ strtolower($location) }}.{{$model_name}}.show',$data);
 </textarea>
</td>

<td>
<h4>Edit File</h4>
<textarea name="" id="" cols="60" rows="10">
//Check authentication
if (!Auth::user()->can('{{$model_name}}.update')){
abort(403,'Unauthorized Action');
}
$data['{{$model_name}}'] = {{ucwords($model_name)}}::findOrFail($id);
return view('{{ strtolower($location) }}.{{$model_name}}.edit',$data);
</textarea>
</td>
</tr>
{{-- Store and Update    --}}
<tr>
<td>
<h4>Store File</h4>
<textarea name="" id="" cols="80" rows="20">
//Check authentication
if (!Auth::user()->can('{{$model_name}}.create')){
    abort(403,'Unauthorized Action');
}

    $request->validate([
@foreach($items as $item)
'{{ str_replace(' ', '_', $item) }}' => ['required','string','min:3','max:40'],
@endforeach
],[
@foreach($items as $item)
    '{{ str_replace(' ', '_', $item) }}.required' => 'Please input {{ $item }}',
@endforeach
]);
@foreach($items as $item)
$data['{{ str_replace(' ', '_', $item) }}'] = $request->{{ str_replace(' ', '_', $item) }};
@endforeach

${{$model_name}} = {{ucwords($model_name)}}::create($data);

return redirect()->back()->with('success','Successfully Create a new {{ucwords($model_name)}}');
</textarea>
</td>

<td>
<h4>Update File</h4>
<textarea name="" id="" cols="60" rows="20">
//Check authentication
if (!Auth::user()->can('{{$model_name}}.update')){
abort(403,'Unauthorized Action');
}
$check{{ucwords($model_name)}} = {{ucwords($model_name)}}::findOrFail(${{$model_name}}->id);
    $request->validate([
@foreach($items as $item)
    '{{ str_replace(' ', '_', $item) }}' => ['required','string','min:3','max:40'],
@endforeach
     'photo' => ['nullable','mimes:jpeg,jpg,png,gif','max:2048'],
],[
@foreach($items as $item)
    '{{ str_replace(' ', '_', $item) }}.required' => 'Please input {{ $item }}',
@endforeach
]);
@foreach($items as $item)
    $data['{{ str_replace(' ', '_', $item) }}'] = $request->{{ str_replace(' ', '_', $item) }};
@endforeach
${{$model_name}} = {{ucwords($model_name)}}::create($data);
DB::table('{{$model_name}}')
            ->where('id',${{$model_name}}['id'])
            ->update($data);
return redirect()->back()->with('success','Successfully Create a new {{ucwords($model_name)}}');
</textarea>
</td>
</tr>

{{-- destroy   --}}
<tr>
<td>
<h4>Destroy File</h4>
<textarea name="" id="" cols="70" rows="20">
//Check authentication
if (!Auth::user()->can('{{$model_name}}.delete')){
    abort(403,'Unauthorized Action');
}
$check{{ucwords($model_name)}} = {{ucwords($model_name)}}::findOrFail(${{$model_name}}->id);

if (!is_null(${{$model_name}})){
${{$model_name}}->delete();
}

return redirect()->back()->with('success','{{ucwords($model_name)}} delete Successfully');
 </textarea>
</td>

<td>
<h4>Next File</h4>
<textarea name="" id="" cols="60" rows="10" readonly>
next
</textarea>
</td>
</tr>



</table>






</div>



</body>
</html>
