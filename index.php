<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=1,initial-scale=1,user-scalable=1" />
	<title>Connect</title>
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="dist/css/style.css?v=1.1">
	<link rel="stylesheet" href="dist/css/adminlte.min.css">
	<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Poppins" />
    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/sweetalert2/sweetalert2.all.js"></script>
    <script src="dist/js/connect.js?v=1.1"></script>
</head>
<body class="bodyAuth">
    <div class="centerDiv">
        <div class="divAuth">
            <div class="divLogo text-center">
                <img class="logoAccess" src="dist/img/connect_logo.png" alt="Connect">
            </div>
            <div class="form-group">
                <label><b>Usuario</b></label>
                <input type="text" id="txtUser" class="form-control input-lg text-center" maxlength="15" autofocus/>
            </div>
            <div class="form-group">
                <div class="pull-right"></div>
                <label><b>Clave</b></label>
                <input type="password" id="txtPass" class="form-control input-lg text-center" maxlength="15" onKeyPress="enter(event, 'cmdAccess')"/>
            </div>
            <div class="form-group">
                <button id="cmdAccess" class="btn btn-primary active btnAccess" type="button" onclick="access()">
                    <i class="fa fa-sign-in-alt" aria-hidden="true"></i>
                    Ingresar
                </button>
            </div>
            <div class="footerAuth center-block">
                KDOCE Soluciones Educativas <img class="imgFlag" src="dist/img/bandera_chile.png" alt="bandera"><br>
                <b>Creado por:</b> Oficina de Desarrollo<br>
                @2025 Todos los derechos reservados
            </div>
        </div>
    </div>
</body>
</html>
