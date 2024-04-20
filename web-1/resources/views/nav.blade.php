<div class="nav">
    @auth
    <a class="button btn btn-success btn-ghost newq" href="{{ route('view_messages') }}">Messages</a>
    <a class="button btn btn-primary btn-ghost newq" href="{{ route('send_message_page') }}">Send Message</a>
    <a class="button btn btn-primary btn-ghost newq" href="{{ route('update_profile_page',Auth::user()->id) }}">Profile</a>
    <a class=" btn btn-default btn-ghost skip" href="{{ route('logout') }}">Logout</a>    
    @endauth
    @guest
    <a class="button btn btn-success btn-ghost newq" href="{{ route('login_page') }}">Login</a>
    <a class="button btn btn-primary btn-ghost newq" href="{{ route('registration_page') }}">Register</a>
    @endguest
</div>