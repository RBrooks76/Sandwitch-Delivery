<div class="panel panel-default" style="box-shadow: 0 1px 1px rgba(0,0,0,.05);">
    <div class="panel-heading" style="padding: 20px;color: #2c3e50;border-color: #ecf0f1;background-color: #ecf0f1;">
        Hi , Iam {{$name}}
    </div>
    <div style="border:1px solid #ecf0f1;padding: 20px;" class="panel-body">
        <p>
            {!! $subject !!}
        </p>
        <p>
            First Name : {{$f_name}}
        </p>
        <p>
            Second Name : {{$l_name}}
        </p>
        <p>
            Phone : {{$phone}}
        </p>
    </div>
    <div class="panel-footer" style="padding: 20px;color: #2c3e50;border-color: #ecf0f1;background-color: #ecf0f1;">
        <p>Sender email : {{$email2}}</p>
    </div>
</div>
