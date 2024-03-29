<ul class="nav">
    <li class="nav-item nav-profile">
        <div class="nav-link">
            <div class="user-wrapper">
                <div class="profile-image">
                    @if (Auth::user()->gambar == '')
                        <img src="{{ asset('images/user/default.png') }}" alt="profile image">
                    @else
                        <img src="{{ asset('images/user/' . Auth::user()->gambar) }}" alt="profile image">
                    @endif
                </div>
                <div class="text-wrapper">
                    <p class="profile-name">{{ Auth::user()->name }}</p>
                    <div>
                        <small class="designation text-muted"
                            style="text-transform: uppercase;letter-spacing: 1px;">{{ Auth::user()->level }}</small>
                        <span class="status-indicator online"></span>
                    </div>
                </div>
            </div>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ url('home') }}">
            <i class="menu-icon mdi mdi-television"></i>
            <span class="menu-title">Dashboard</span>
        </a>
    </li>
    @if (Auth::user()->level == 'admin')
        <li class="nav-item">
            <a class="nav-link " data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                <i class="menu-icon mdi mdi-content-copy"></i>
                <span class="menu-title">Master Data</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{ url('masterlayanan') }}">Data Layanan</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('user') }}">Data User</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('service') }}">Data Service</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('subservice') }}">Data Sub Service</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('mprogress') }}">Data Progress</a></li>
                </ul>
            </div>
        </li>
    @endif

    {{-- @if (session('infoUser')['ESELON'] <= '40' || session('infoUser')['ESELON'] == 'D0') --}}
        <li class="nav-item">
            <a class="nav-link" href="{{ url('approvetiket') }}">
                <i class="menu-icon fa fa-handshake-o"></i>
                <span class="menu-title">Approve Tiket</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ url('persetujuantiket') }}">
                <i class="menu-icon fa fa-book"></i>
                <span class="menu-title">Persetujuan Tiket</span>
            </a>
        </li>
    {{-- @endif --}}

    <li class="nav-item">
      <a class="nav-link" href="{{url('tiket')}}">
        <i class="menu-icon fa fa-ticket"></i>
        <span class="menu-title">Tiket</span>
      </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ url('tiket2') }}">
            <i class="menu-icon fa fa-ticket"></i>
            <span class="menu-title">Tiket V2</span>
        </a>
    </li>
    @if (session('infoUser')['ESELON'] <= '20' && session('infoUser')['PERUSAHAAN'] == 'H0000000')
    <li class="nav-item">
        <a class="nav-link" href="{{ url('tugasku') }}">
            <i class="menu-icon fa fa-cubes"></i>
            <span class="menu-title">Waiting Approval</span>
        </a>
    </li>   
    @else 
    <li class="nav-item">
        <a class="nav-link" href="{{ url('tugasku') }}">
            <i class="menu-icon fa fa-cubes"></i>
            <span class="menu-title">Tugasku</span>
        </a>
    </li>
    @endif
    <li class="nav-item">
        <a class="nav-link" href="{{ url('saran') }}">
            <i class="menu-icon fa fa-comments"></i>
            <span class="menu-title">Kritik Saran</span>
        </a>
    </li>
    {{-- @if (session('infoUser')['BIROBU'] == 'B31050000')
        <li class="nav-item">
            <a class="nav-link" href="{{ url('saran') }}">
                <i class="menu-icon fa fa-book"></i>
                <span class="menu-title">List Kritik Saran</span>
            </a>
        </li>
    @endif --}}
</ul>
