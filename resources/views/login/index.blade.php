<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Servicedesk Silog Group</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('vendors/iconfonts/mdi/css/materialdesignicons.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendors/iconfonts/puse-icons-feather/feather.css')}}">
    <link rel="stylesheet" href="{{asset('vendors/css/vendor.bundle.base.css')}}">
    <link rel="stylesheet" href="{{asset('vendors/css/vendor.bundle.addons.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{asset('favicon3.ico')}}" />

</head>

<body id="background">
    <form method="post" action="{{ url('/login') }}">
        {{ csrf_field() }}
        <div class="container-scroller">
            <div class="container-fluid page-body-wrapper full-page-wrapper auth-page">
                <div class="content-wrapper d-flex align-items-center auth theme-one">

                    <div class="row w-100">
                        <div class="col-md-12" style="margin-bottom: 20px;">

                        </div>
                        <div class="col-lg-4 mx-auto">
                            <div class="auto-form-wrapper" style="opacity: 0.9;border-radius:15px">
                                <h3 style="text-align: center;color:#000080">Service Desk</h3>
                                <h4 style="text-align: center;color:#000080;margin-bottom:30px">Semen Indonesia Logistik
                                    Group</h4>
                                <div class="form-group">
                                    <label class="label">Username SISIL</label>
                                    <div class="input-group">
                                        <input id="email" type="text" class="form-control" name="email"
                                            value="{{ old('email') }}" required autofocus>
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="mdi mdi-check-circle-outline"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="label">Password SISIL</label>
                                    <div class="input-group">
                                        <input id="password" type="password" class="form-control" name="password"
                                            required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="mdi mdi-check-circle-outline"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button class="btn submit-btn btn-block" type="submit" style="background-color: #000080;color:white">Login</button>
                                </div>
                                @if (session('pesan'))
                                <div class="alert alert-danger">
                                    {{ session('pesan') }}
                                </div>
                                @endif
                                <p class="footer-text text-center" style="margin-top: 20px;color: #000080">Copyright ©
                                    {{date('Y')}} Servicedesk Silog Group - All rights reserved.</p>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- content-wrapper ends Herziwp@gmail.com -->
            </div>
            <!-- page-body-wrapper ends -->
        </div>
    </form>
    <script src="{{asset('vendors/js/vendor.bundle.base.js')}}"></script>
    <script src="{{asset('vendors/js/vendor.bundle.addons.js')}}"></script>
</body>

</html>
