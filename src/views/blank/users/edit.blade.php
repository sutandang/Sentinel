@extends(Config::get('Sentinel::views.layout'))

{{-- Web site Title --}}
@section('title')
@parent
Edit Profile
@stop

{{-- Content --}}
@section('content')

<h1>Edit 
@if ($user->email == Sentry::getUser()->email)
	Your
@else 
	{{ $user->email }}'s 
@endif 
Profile</h1>

<?php $customFields = Config::get('Sentinel::auth.additional_user_fields'); ?>

@if (! empty($customFields))
<div>
    <h4>Profile</h4>
    <form method="POST" action="{{ route('sentinel.users.update', $user->id) }}" accept-charset="UTF-8" class="form-horizontal" role="form">

        @foreach(Config::get('Sentinel::auth.additional_user_fields') as $field => $rules)
        <p>
            <label for="{{ $field }}">{{ ucwords(str_replace('_',' ',$field)) }}</label>
            <input class="form-control" name="{{ $field }}" type="text" value="  {{ $user->$field }}">
            {{ ($errors->has($field) ? $errors->first($field) : '') }}
        </p>
        @endforeach

        <p>         
            <input name="_method" value="PUT" type="hidden">
            <input name="_token" value="{{ csrf_token() }}" type="hidden">
            <input name="id" value="{{ $user->id }}" type="hidden">
            <input class="btn btn-primary" value="Submit Changes" type="submit">
        </p>

    </form>
</div>

<hr />

@if (Sentry::getUser()->hasAccess('admin') && ($user->id != Sentry::getUser()->id))
<div class="row">
    <h4>Group Memberships</h4>
    <form method="POST" action="{{ route('sentinel.users.memberships', $user->id) }}" accept-charset="UTF-8" class="form-horizontal" role="form">

        @foreach($groups as $group)
        <label class="checkbox-inline">
            <input type="checkbox" name="groups[{{ $group->name }}]" value="1" {{ ($user->inGroup($group) ? 'checked' : '') }}> {{ $group->name }}
        </label>
        @endforeach

        <input name="_token" value="{{ csrf_token() }}" type="hidden">
        <input value="Update Memberships" type="submit">
    </form>
</div>
<hr />
@endif

<h4>Change Password</h4>
<form method="POST" action="{{ route('sentinel.change.password', $user->id) }}" accept-charset="UTF-8" class="form-inline" role="form">
        
    @if(! Sentry::getUser()->hasAccess('admin'))
    <p>
       <label for="oldPassword">Old Password</label>
       <input placeholder="Old Password" name="oldPassword" value="" id="oldPassword" type="password">
       {{ ($errors->has('oldPassword') ? '<br />' . $errors->first('oldPassword') : '') }}
    </p>
    @endif

    <p>
        <label for="newPassword">New Password</label>
        <input placeholder="New Password" name="newPassword" value="" id="newPassword" type="password">
        {{ ($errors->has('newPassword') ?  '<br />' . $errors->first('newPassword') : '') }}
    </p>

    <p>
        <label for="newPassword_confirmation">Confirm New Password</label>
        <input placeholder="Confirm New Password" name="newPassword_confirmation" value="" id="newPassword_confirmation" type="password">
        {{ ($errors->has('newPassword_confirmation') ? '<br />' . $errors->first('newPassword_confirmation') : '') }}
    </p>

    <input name="_token" value="{{ csrf_token() }}" type="hidden">
    <input class="btn btn-primary" value="Change Password" type="submit">

</form>

@stop