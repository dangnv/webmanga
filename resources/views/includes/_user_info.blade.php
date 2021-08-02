@php $user = \Illuminate\Support\Facades\Auth::user(); @endphp
<img src="{{ $user->avatar }}" />
<span class="lbl-user-name for-mobile" data-toggle="tooltip" data-placement="top" title="{{ $user->name }}">{{ \App\Models\User::convertName($user->name) }}</span>
<span class="for-desktop">{{ $user->name }}</span>
