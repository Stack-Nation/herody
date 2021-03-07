<div class="vertical-nav" id="sidebar" style="margin-top:6em;">
	<div class="dashbord_nav_list">
		<ul>
			<li class="{{Request::is('company/dashboard')? 'active':''}}"><a href="{{route('employer.dashboard')}}">Dashboard</a></li>
			<li class="{{Request::is('company/profile')? 'active':''}}"><a href="{{route('employer.profile')}}">Company Profile</a></li>
			<li class="{{Request::is('company/works/post')? 'active':''}}"><a href="{{route('employer.work.post')}}">Post a New Work</a></li>
			<li class="{{Request::is('company/works')? 'active':''}}"><a href="{{route('employer.work.manage')}}">Manage Works</a></li>
			<li class="{{Request::is('company/wallet')? 'active':''}}"><a href="{{route('employer.wallet')}}">Wallet</a></li>
			<li class="{{Request::is('company/chats')? 'active':''}}"><a href="{{route('employer.chats')}}">Chats</a></li>
			<li class="{{Request::is('company/change-pass')? 'active':''}}"><a href="{{route('employer.changepass')}}">Change Password</a></li>
			<li><a href="{{route('employer.logout')}}"><span class="flaticon-logout"></span> Logout</a></li>
		</ul>
	</div>
</div>