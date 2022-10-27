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
    <h2 align="center">Apolable - Dynamically Controller MODEL and Migration Code Creation</h2>
    <div class="form-group">
        <form name="add_name" action="{{ route('code.store') }}" method="post">
             @csrf
            <div class="col-md-8">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dynamic_field">
                        <tr>
                            <td><input type="text" name="model_name" placeholder="Enter Model Name" class="form-control" />
                                <p>
                                @error('model_name')
                                <p style="color: red;">{{ $message }}</p>
                                @enderror
                                </p>
                            </td>
                            <td>
                                <select name="location" id="" class="form-control">
                                    <option>Backend</option>
                                    <option>Frontend</option>
                                </select>

                                <p>
                                @error('location')
                                <p style="color: red;">{{ $message }}</p>
                                @enderror
                                </p>

                            </td>
                        </tr>
                        <tr>
                            <td><input type="text" name="item_name[]" placeholder="Enter field Name" class="form-control name_list" /></td>
                            @error('name2')
                            <p style="color: red;">{{ $message }}</p>
                            @enderror
                        </tr>
                    </table>
                </div>
                <button type="button" name="add" id="add" class="btn btn-success">Add More</button>
                <button type="submit" class="btn btn-primary">Generate</button>
            </div>

        </form>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function(){
        var postURL = "<?php echo url('addmore'); ?>";
        var i=1;

        $('#add').click(function(){
            i++;
            $('#dynamic_field').append('<tr id="row'+i+'" class="dynamic-added"><td><input type="text" name="item_name[]" placeholder="Enter field name" class="form-control name_list" /></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');
        });

        $(document).on('click', '.btn_remove', function(){
            var button_id = $(this).attr("id");
            $('#row'+button_id+'').remove();
        });
    });
</script>
</body>
</html>
