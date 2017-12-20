<div class="form-group {!! !$errors->has($errorKey) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="col-sm-8">

        @include('admin::form.error')

        <script type="text/plain" id="UEditor" placeholder="{{ $placeholder }}" style="height: 300px">
            {!! $attributes !!} {!! old($column, $value) !!}
        </script>

        @include('admin::form.help-block')

    </div>
</div>