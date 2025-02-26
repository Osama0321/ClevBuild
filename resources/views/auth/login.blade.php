<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ClevBuild | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('Admin/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('Admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('Admin/dist/css/adminlte.min.css') }}">
  <!-- custom css link -->
  <link rel="stylesheet" href="{{ asset('Admin/css/custom.css') }}">
</head>

<body class="hold-transition login-page">
  <div class="login_page_Wrapper">
     <div class="login_left">
        <div class="login_bg">
          <img src="{{ asset('images/loginPage_image.png') }}" />
        </div>
        <div class="login-left-text">
          <p>ClevBuild is construction management platform used by over 1,000,000 projects worldwide</p>
        </div>
     </div>
     <div class="login-box">
  <!-- <div class="login-logo">
    <a href="{{ asset('Admin/index2.html') }}"><b>Admin</b>LTE</a>
  </div> -->
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
        <h2>Login</h2>
        <p class="login-box-msg">Login credential has been email to you, once you login you can change the password</p>
      <form action="{{ route('logedin') }}" method="post">
        @csrf
        @include('message')
        <div class="input-group mb-3">
          <label for="email">
            Email
          </label>
          <input type="email" name="email" class="form-control" placeholder="Email">
          <!-- <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div> -->
        </div>
        <div class="input-group mb-3">
          <label for="password">Password</label>
          <input type="password" name="pass" class="form-control" placeholder="Password">
          <!-- <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div> -->
        </div>
        <div class="row">
          <div class="col-12 p-0">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div>
          <!-- <div class="col-12 p-0">
            <div class="Forgot_text">
              <a href="#" class="forgot_link">Forgot Password?</a>
            </div>
          </div> -->
          <div class="login_submit_btn mt-3">
            <button type="submit" class="btn btn-block btn-primary">Log In</button>
          </div>
        </div>
      </form>
      
      <!-- <br>
      <a href="{{ route('register') }}" style="color:#3173ff;" class="btn btn-block btn-light btn-xs">Create Account</a> -->
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
  </div>

<!-- /.login-box -->

<!-- jQuery -->
<script src="{{ asset('Admin/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('Admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('Admin/dist/js/adminlte.min.js') }}"></script>
</body>
</html>
