<div class="vertical-nav" id="sidebar" style="margin-top:6em;">
<div class="dashbord_nav_list">
	<ul>
		<li class="{{Request::is('user/dashboard')? 'active':''}}"><a href="{{route('user.dashboard')}}">Dashboard</a></li>
		<li class="{{Request::is('user/profile')? 'active':''}}"><a href="{{route('user.profile')}}">Profile</a></li>
		<li class="{{Request::is('user/resume')? 'active':''}}"><a href="{{route('user.resume')}}">Resume</a></li>
		<li class="{{Request::is('user/works')? 'active':''}}"><a href="{{route('user.works.show')}}">Applied Works</a></li>
		<li class="{{Request::is('user/wallet')? 'active':''}}"><a href="{{route('user.wallet')}}">Wallet</a></li>
		<li class="{{Request::is('user/chats')? 'active':''}}"><a href="{{route('user.chats')}}">Chats</a></li>
		<li class="{{Request::is('user/change-pass')? 'active':''}}"><a href="{{route('user.changePassword')}}">Change Password</a></li>
		<li><a href="{{route('user.logout')}}"> Logout</a></li>
	</ul>
</div>
</div>