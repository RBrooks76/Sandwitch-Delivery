@if(session()->has('error'))
    <div class="alert alert-danger">
        <p>{{session()->get('error')}}</p>
    </div>
@endif
@if(session()->has('success'))
    <div class="alert alert-success">
        <p>{{session()->get('success')}}</p>
    </div>
@endif
@if(session()->has('warning'))
    <div class="alert alert-warning">
        <p>{{session()->get('warning')}}</p>
    </div>
@endif
@if(session()->has('info'))
    <div class="alert alert-info">
        <p>{{session()->get('info')}}</p>
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif