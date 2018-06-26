@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Number Verification</div>
                <div class="panel-body">
                    <form id="upload" action="{{ route('upload') }}" onsubmit="return validateForm()"  method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="col-md-6">
                            <h3>Insert .xlsx file</h3>
                            <div class="form-group">
                                <label for="file">Select file to upload:</label>
                                <input class="form-control" type="file" name="file" id="file">
                                <label for="country">Country:</label>
                                <select id ="country" name ="country" class ="form-control">
                                    <option value="1">Indonesia</option>
                                    <option value="2">China</option>
                                    <option value="3">Thailand</option>
                                    <option value="4">Malaysia</option>
                                    <option value="5">Vietnam</option>
                                </select>
                            </div>
                            {{-- <input id="submit" class="btn btn-primary" type="submit" value="Verify" name="submit"> --}}
                            <button  id="submit" class="btn btn-primary" type="submit">Verify</button>
                            <button style="display: none;" class="btn btn-primary loading" type="button" disabled><i id="spiner" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></i> Loading..</button>
                            <span style="display: none;" id="info" class="loading">Please wait, this will take few minute(s).</span>
                        </div>
                        <div class="col-md-6">
                            <div style="vertical-align: 10px;">
                                @if (session('status'))
                                    <div id="download_link">
                                        <h3>Your file is ready!</h3>
                                        <a class="btn btn-success" href="{{route('download',['name' => session('status')])}}">Download</a>
                                    </div>
                                @endif
                                @if (session('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('customjs')
<script type="text/javascript">
    $('#submit').click(function(){
        $('.loading').show();
        $('#file').attr("readonly", true);
        $('#country').attr("readonly", true);
        $('.alert').hide();
        $('#download_link').hide();
        $(this).hide();
    });

    function validateForm() {
        $("#country").find(":selected").text();
        if(confirm('The country you selected is '+$("#country").find(":selected").text()+'')){
            return true;
        }else{
            location.reload();
            return false;
        }
    }
</script>
@endsection