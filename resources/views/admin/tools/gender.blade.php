<div class="form-group">

    <form action="{{ route('users.index') }}" method="get">
        <div class="col-sm-2"  data-toggle="buttons">
            @foreach($options as $option => $label)
                <label class="btn btn-default btn-sm {{ \Request::get('gender', 'all') == $option ? 'active' : '' }}">
                    <input type="radio" class="user-gender" value="{{ $option }}">{{$label}}
                </label>
            @endforeach
        </div>
        <div class="col-sm-2">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                <input type="text" name="name" class="form-control text" value="{{ request()->name }}">
            </div>
        </div>
        <div class="col-sm-1" style="margin-left: -25px !important;"><button type="submit" class="btn btn-info">查询</button></div>
    </form>
</div>