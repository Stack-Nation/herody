<div class="vertical-nav" id="sidebar" style="margin-top:6em;">
<div class="dashbord_nav_list">
	<ul>
		<li class="{{Request::is('user/dashboard')? 'active':''}}"><a href="{{route('user.dashboard')}}"><span class="flaticon-dashboard"></span> Dashboard</a></li>
		<li class="{{Request::is('user/profile')? 'active':''}}"><a href="{{route('user.profile')}}"><span class="flaticon-profile"></span> Profile</a></li>
		<li class="{{Request::is('user/resume')? 'active':''}}"><a href="{{route('user.resume')}}"><span class="flaticon-resume"></span> Resume</a></li>
		<li class="{{Request::is('user/works')? 'active':''}}"><a href="{{route('user.works.show')}}"><span class="flaticon-resume"></span> Applied Works</a></li>
		<li class="{{Request::is('user/withdraw')? 'active':''}}"><a href="{{route('user.withdraw')}}"><span class="flaticon-locked"></span> Wallet</a></li>
		<li class="{{Request::is('user/change-pass')? 'active':''}}"><a href="{{route('user.changePassword')}}"><span class="flaticon-locked"></span> Change Password</a></li>
		<li><a href="{{route('user.logout')}}"><span class="flaticon-logout"></span> Logout</a></li>
	</ul>
</div>
</div>