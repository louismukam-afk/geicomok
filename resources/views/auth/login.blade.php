<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{'Login - GEICOM'}}</title>
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('css/font-awesome.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/custom.css')}}">

    <script src="{{asset('js/jquery.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>

    <style>
        ::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
            color: lightgrey;
            font-weight: 600;
            opacity: 1; /* Firefox */
        }

        :-ms-input-placeholder { /* Internet Explorer 10-11 */
            font-weight: 600;
            color: lightgrey;
        }

        ::-ms-input-placeholder { /* Microsoft Edge */
            font-weight: 600;
            color: lightgrey;
        }

        body
        {
            background: url('images/comm.jpg') fixed;
            background-size: cover;
            padding: 0;
            margin: 0;
        }

        .wrap
        {
            width: 100%;
            height: 100%;
            min-height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 99;
        }

        p.form-title
        {
            font-family: 'Open Sans' , sans-serif;
            font-size: 20px;
            font-weight: 600;
            text-align: center;
            color: #FFFFFF;
            margin-top: 5%;
            text-transform: uppercase;
            letter-spacing: 4px;
        }

        form
        {
            width: 350px;
            margin: 0 auto;
        }

        form.login input[type="text"], form.login input[type="password"]
        {
            width: 100%;
            margin: 0;
            padding: 5px 10px;
            background: 0;
            border: 0;
            border-bottom: 1px solid #FFFFFF;
            outline: 0;
            font-style: italic;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 1px;
            margin-bottom: 5px;
            color: white;
            outline: 0;
        }

        form.login input[type="submit"]
        {
            width: 100%;
            font-size: 14px;
            text-transform: uppercase;
            font-weight: 500;
            margin-top: 16px;
            outline: 0;
            cursor: pointer;
            letter-spacing: 1px;
        }

        form.login input[type="submit"]:hover
        {
            transition: background-color 0.5s ease;
        }

        form.login .remember-forgot
        {
            float: left;
            width: 100%;
            margin: 10px 0 0 0;
        }
        form.login .forgot-pass-content
        {
            min-height: 20px;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        form.login label, form.login a
        {
            font-size: 12px;
            font-weight: 400;
            color: #FFFFFF;
        }

        form.login a
        {
            transition: color 0.5s ease;
        }

        form.login a:hover
        {
            color: #2ecc71;
        }

        .pr-wrap
        {
            width: 100%;
            height: 100%;
            min-height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 999;
            display: none;
        }

        .show-pass-reset
        {
            display: block !important;
        }

        .pass-reset
        {
            margin: 0 auto;
            width: 250px;
            position: relative;
            margin-top: 22%;
            z-index: 999;
            background: #FFFFFF;
            padding: 20px 15px;
        }

        .pass-reset label
        {
            font-size: 12px;
            font-weight: 400;
            margin-bottom: 15px;
        }

        .pass-reset input[type="email"]
        {
            width: 100%;
            margin: 5px 0 0 0;
            padding: 5px 10px;
            background: 0;
            border: 0;
            border-bottom: 1px solid #000000;
            outline: 0;
            font-style: italic;
            font-size: 12px;
            font-weight: 400;
            letter-spacing: 1px;
            margin-bottom: 5px;
            color: #000000;
            outline: 0;
        }

        .pass-reset input[type="submit"]
        {
            width: 100%;
            border: 0;
            font-size: 14px;
            text-transform: uppercase;
            font-weight: 500;
            margin-top: 10px;
            outline: 0;
            cursor: pointer;
            letter-spacing: 1px;
        }

        .pass-reset input[type="submit"]:hover
        {
            transition: background-color 0.5s ease;
        }
        .posted-by
        {
            position: absolute;
            bottom: 26px;
            margin: 0 auto;
            color: #FFF;
            background-color: rgba(0, 0, 0, 0.66);
            padding: 10px;
            left: 45%;
        }

    </style>

</head>
<body>


<!------ Include the above in your HEAD tag ---------->

<div class="container">
    <div class="row">
        <div class="col-md-12">

            <div class="wrap">
                <p class="form-title">
                    Connexion</p>
                <form class="login" method="post" action="{{route('attempt_login')}}">
                    @if(count($errors)>0)
                        <div class="alert alert-danger">
                            <ul >
                                @foreach($errors->all() as $e)
                                    <li><strong>{{$e}}</strong></li>
                                @endforeach
                            </ul>
                        </div>


                    @endif
                    {{csrf_field()}}
                    <input type="text" placeholder="Nom d'utitlisateur" name="username" value="{{old('username')}}"  autofocus required/>
                    <input type="password" name="password" placeholder="Mot de passe" required />
                    <input type="submit" value="Valider" class="btn btn-success btn-sm" />
                    <div class="remember-forgot">
                        <div class="row" >
                            <div class="col-md-6">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"/>
                                        Se souvenir de moi
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6 forgot-pass-content" >
                                <a  href="{{route('register')}}" class="forgot-pass pull-right">S'enregistrer</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="posted-by"> By: <a href="http://youth-concept.com" target="_blank">Youth-concept</a></div>
</div>


<script>
    $(document).ready(function () {
        $('.forgot-pass').click(function(event) {
            $(".pr-wrap").toggleClass("show-pass-reset");
        });

        $('.pass-reset-submit').click(function(event) {
            $(".pr-wrap").removeClass("show-pass-reset");
        });
    });
</script>
</body>

</html>