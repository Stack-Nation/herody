<div class="vertical-nav" id="sidebar" style="margin-top:6em;">
	<div class="dashbord_nav_list">
		<ul>
			<li class="{{Request::is('company/dashboard')? 'active':''}}"><a href="{{route('employer.dashboard')}}"><span class="flaticon-dashboard"></span> Dashboard</a></li>
			<li class="{{Request::is('company/profile')? 'active':''}}"><a href="{{route('employer.profile')}}"><span class="flaticon-profile"></span> Company Profile</a></li>
			<li class="{{Request::is('company/works/post')? 'active':''}}"><a href="{{route('employer.work.post')}}"><span class="flaticon-resume"></span> Post a New Work</a></li>
			<li class="{{Request::is('company/works')? 'active':''}}"><a href="{{route('employer.work.manage')}}"><span class="flaticon-paper-plane"></span> Manage Works</a></li>
			<li class="{{Request::is('company/change-pass')? 'active':''}}"><a href="{{route('employer.changepass')}}"><span class="flaticon-locked"></span> Change Password</a></li>
			<li><a href="{{route('employer.logout')}}"><span class="flaticon-logout"></span> Logout</a></li>
		</ul>
	</div>
</div>