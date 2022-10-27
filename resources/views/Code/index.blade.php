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
    <h2>Controller File</h2>
    <code>
<textarea name="" id="" cols="150" rows="20">
{{ strtr('<? php',[' '=>'']) }}

namespace App\Http\Controllers\{{  ucfirst($location) }};

use App\Http\Controllers\Controller;
use App\Models\{{ $model_name }};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class {{ ucwords($model_name) }}Controller extends Controller
{

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function index()
    {
        //Check authentication
          if (!Auth::user()->can('{{ strtolower($model_name) }}.all')){
          abort(403,'Unauthorized Action');
        }
        $data['pageTitle'] = "{{ ucwords($model_name) }}";
        $data['{{ strtolower($model_name) }}s'] = {{ ucwords($model_name) }}::where('status','active')->orderBy('created_at','DESC')->get();
        return view('{{strtolower($location) }}.{{ strtolower($model_name) }}.index',$data);

    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        //Check authentication
        if (!Auth::user()->can('{{ strtolower($model_name) }}.create')){
        abort(403,'Unauthorized Action');
        }
        return view('{{strtolower($location) }}.{{ strtolower($model_name) }}.create');
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
    //Check authentication
    if (!Auth::user()->can('{{ strtolower($model_name) }}.create')){
    abort(403,'Unauthorized Action');
    }

    $request->validate([
    @foreach($items as $item)
    '{{ str_replace(' ', '_', $item) }}' => ['nullable','string','min:3','max:255'],
    @endforeach
    ],[
    @foreach($items as $item)
    '{{ str_replace(' ', '_', $item) }}.required' => 'Please input {{ $item }}',
    @endforeach
    ]);
    @foreach($items as $item)
    $data['{{ str_replace(' ', '_', $item) }}'] = $request->{{ str_replace(' ', '_', $item) }};
    @endforeach
    $data['status'] = $request->status;
        $data['slug'] = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-',$request->title))); // to get unique slug add...

    //dd($data);

    //{{ strtolower($model_name) }} photo
    if($request->hasFile('photo')){
        $file = $request->file('photo');
        $path = 'frontend/images/{{ strtolower($model_name) }}';
        $file_name = 'photo_'.rand(000000000,999999999).'.'.$file->getClientOriginalExtension();
        $file->move(public_path($path),$file_name);
        $data['photo'] = $path.'/'.$file_name;
    }
        ${{ strtolower($model_name) }} = {{ ucfirst($model_name) }}::create($data);
        return redirect()->back()->with('success','Successfully Create a new {{ ucfirst($model_name) }}');
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Models\{{ ucfirst($model_name) }}  ${{ strtolower($model_name) }}
    * @return \Illuminate\Http\Response
    */
    public function show({{ ucfirst($model_name) }} ${{ strtolower($model_name) }},$id)
    {
        //Check authentication
        if (!Auth::user()->can('{{ strtolower($model_name) }}.show')){
        abort(403,'Unauthorized Action');
        }
        $data['{{ strtolower($model_name) }}'] = {{ ucfirst($model_name) }}::findOrFail($id);
        return view('{{strtolower($location) }}.{{ strtolower($model_name) }}.show',$data);

    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\{{ ucfirst($model_name) }}  ${{ strtolower($model_name) }}
    * @return \Illuminate\Http\Response
    */
    public function edit({{ ucfirst($model_name) }} ${{ strtolower($model_name) }})
    {
        //Check authentication
        if (!Auth::user()->can('{{ strtolower($model_name) }}.update')){
        abort(403,'Unauthorized Action');
        }
        $data['pageTitle'] = "{{ ucwords($model_name) }}";
        $data['{{ strtolower($model_name) }}'] = {{ ucfirst($model_name) }}::findOrFail($id);
        return view('{{strtolower($location) }}.{{ strtolower($model_name) }}.edit',$data);

    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\{{ ucfirst($model_name) }}  ${{ strtolower($model_name) }}
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
    {
        //Check authentication
        if (!Auth::user()->can('{{ strtolower($model_name) }}.update')){
        abort(403,'Unauthorized Action');
        }
        $check{{ ucfirst($model_name) }} = {{ ucfirst($model_name) }}::findOrFail($id);

    $request->validate([
            '{{ strtolower($model_name) }}' => ['required','string', 'min:3','max:255','unique:{{ strtolower($model_name) }}s,id,'.$request->id],
    @foreach($items as $item)
        '{{ str_replace(' ', '_', $item) }}' => ['nullable','string','min:3','max:255'],
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
        $data['status'] = $request->status;
        $data['slug'] = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-',$request->title))); // to get unique slug add...

   //{{ strtolower($model_name) }} photo
    if($request->hasFile('photo')){
        $file = $request->file('photo');
        $path = 'frontend/images/{{ strtolower($model_name) }}';
        $file_name = 'photo_'.rand(000000000,999999999).'.'.$file->getClientOriginalExtension();
        $file->move(public_path($path),$file_name);
        $data['photo'] = $path.'/'.$file_name;

        if(file_exists($check{{ ucfirst($model_name) }}->photo)){
            unlink($check{{ ucfirst($model_name) }}->photo);
        }
    }

    DB::table('{{strtolower($model_name)}}s')
            ->where('id',$id)
            ->update($data);
    return redirect()->back()->with('success','Successfully Updated {{ucwords($model_name)}}');

    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\{{ ucfirst($model_name) }}  ${{ strtolower($model_name) }}
    * @return \Illuminate\Http\Response
    */
    public function destroy({{ ucfirst($model_name) }} ${{ strtolower($model_name) }})
    {
        //Check authentication
        if (!Auth::user()->can('{{ strtolower($model_name) }}.delete')){
        abort(403,'Unauthorized Action');
        }
        $check{{ ucfirst($model_name) }} = {{ ucfirst($model_name) }}::findOrFail(${{ strtolower($model_name) }}->id);

        if (!is_null(${{ strtolower($model_name) }})){
        ${{ strtolower($model_name) }}->delete();
        }

        return redirect()->back()->with('success','{{ ucfirst($model_name) }} Deleted Successfully');

    }
}

</textarea>
    </code>

<table class="table table-bordered" id="dynamic_field">
{{--model and migration    --}}
<tr>
<td>
<h2>Model File</h2>
    <code>
<textarea name="" id="" cols="150" rows="10">
{{ strtr('<? php',[' '=>'']) }}

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class {{ ucfirst($model_name) }} extends Model
{
    use HasFactory, HasRoles;
    protected $fillable = [
        @foreach($items as $item)
        '{{ str_replace(' ', '_', $item) }}',
        @endforeach
        'user_id',
        'update_by',
        'status'
    ];

    public function user(){
        return $this->belongsTo(User::class)->withDefault();
    }
    public function updateBy(){
        return $this->belongsTo(User::class,'update_by');
    }
}

</textarea>
    </code>
</td>
</tr>
<tr>
<td>
<h2>Migration File</h2>
    <code>
<textarea name="" id="" cols="150" rows="10">
@foreach($items as $item)
$table->string('{{ str_replace(' ', '_', $item) }}')->nullable();
@endforeach
$table->foreignId('category_id')->constrained();
$table->foreignId('user_id')->constrained();
$table->unsignedBigInteger('update_by')->nullable();  // who updated this
$table->foreign('update_by')->references('id')->on('users');

$table->string('status')->default('active');
$table->softDeletes();
</textarea>
    </code>
</td>
</tr>


</table>

</div>
</body>
</html>
