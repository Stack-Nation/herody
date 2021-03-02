    <!-- Navigation Bar-->
    <header id="topnav" class="defaultscroll scroll-active">
      <!-- Menu Start -->
      <div class="container">
          <!-- Logo container-->
          <div>
              <a href="index.html" class="logo">
                  <img src="{{asset("assets/main/images/logo-dark.png")}}" alt="" class="logo-dark" height="18" />
              </a>
          </div>                 
          <div class="buy-button">
              <a href="post-a-job.html" class="btn btn-primary"><i class="mdi mdi-cloud-upload"></i> Post a Job</a>
          </div><!--end login button-->
          <!-- End Logo container-->
          <div class="menu-extras">
              <div>
                  <!-- Mobile menu toggle-->
                  <a class="navbar-toggle">
                      <div class="lines">
                          <span></span>
                          <span></span>
                          <span></span>
                      </div>
                  </a>
                  <!-- End mobile menu toggle-->
              </div>
          </div>
  
          <div id="navigation">
              <!-- Navigation Menu-->   
              <ul class="navigation-menu">
                  <li @if(Request::route()->getName()==="index") class="active" @endif><a href="{{route("index")}}">Home</a></li>
                  <li>
                    <a href="{{route('projects')}}">Internships</a>
                  </li>
                  <li>
                    <a href="{{route('gigs')}}">Gigs</a>
                  </li>
                  <li>
                    <a href="{{route('campaigns')}}">Projects</a>
                  </li>
                  @if(Auth::check())
                  <li>
                    <a href="{{route('user.withdraw')}}">
                      Wallet
                    </a>
                  </li>
                  <li>
                    <a href="{{route('user.resume')}}">
                      Resume
                    </a>
                  </li>
                  <li>
                    <a href="{{route('user.logout')}}">
                      Logout
                    </a>
                  </li>
                  @endif
                  @if(Auth::guard('employer')->check())
                  <li>
                    <a href="{{route('employer.logout')}}">
                      Logout
                    </a>
                  </li>
                  @endif
              </ul><!--end navigation menu-->
          </div><!--end navigation-->
      </div><!--end container-->
      <!--end end-->
  </header><!--end header-->
  <!-- Navbar End -->