<?php
require '../../include/db_conn.php';
page_protect();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Gym Cañones | Nuevo Miembro</title>

    <!-- CSS base existente -->
    <link rel="stylesheet" href="../../css/style.css" id="style-resource-5">
    <link rel="stylesheet" href="../../css/dashMain.css">
    <link rel="stylesheet" type="text/css" href="../../css/entypo.css">
    <link href="a1style.css" type="text/css" rel="stylesheet">
    <link rel="shortcut icon" href="images/icono.png" type="image/x-icon">

    <script type="text/javascript" src="../../js/Script.js"></script>
</head>

<body class="page-body page-fade ff-register" onload="collapseSidebar()"><!-- scope local -->

<div class="page-container sidebar-collapsed" id="navbarcollapse">

    <div class="sidebar-menu">
        <header class="logo-env">
            <div class="logo">
                <a href="main.php"><img src="../../images/logo2.png" alt="FitFusion Gym" width="192" height="80" /></a>
            </div>
            <div class="sidebar-collapse" onclick="collapseSidebar()">
                <a href="#" class="sidebar-collapse-icon with-animation"><i class="entypo-menu"></i></a>
            </div>
        </header>
        <?php include('nav.php'); ?>
    </div>

    <div class="main-content">

        <div class="row" style="margin-bottom:4px">
            <div class="col-md-6 col-sm-8 clearfix"></div>
            <div class="col-md-6 col-sm-4 clearfix hidden-xs">
                <ul class="list-inline links-list pull-right ff-links-list">
                    <li>Bienvenid@ <?php echo $_SESSION['full_name']; ?></li>
                    <li>
                        <a class="ff-pill" href="logout.php">
                            Cerrar Sesión <i class="entypo-logout right"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <h3 class="ff-page-title">Ingreso de Nuevo Miembro</h3>
        <hr />

        <div class="a1-container a1-small a1-padding-32" style="margin-top:2px; margin-bottom:2px;">
            <div class="a1-card-8 a1-light-gray ff-card">
                <div class="a1-container a1-dark-gray a1-center ff-card-header">
                    <h6>NUEVO INGRESO</h6>
                </div>

                <form id="form1" name="form1" method="post" class="a1-container" action="new_submit.php">
                    <table width="100%" border="0" align="center">
                        <tr>
                            <td>
                                <table width="100%" border="0" align="center" class="ff-form-table">
                                    <tr>
                                        <td>ID MEMBRESÍA:</td>
                                        <td><input type="text" id="boxx" name="m_id" value="<?php echo time(); ?>" readonly required/></td>
                                    </tr>
                                    <tr>
                                        <td>NOMBRE:</td>
                                        <td><input name="u_name" id="boxx" required/></td>
                                    </tr>
                                    <tr>
                                        <td>GÉNERO:</td>
                                        <td>
                                            <select name="gender" id="boxx" required>
                                                <option value="">--Favor Seleccionar--</option>
                                                <option value="Hombre">Hombre</option>
                                                <option value="Mujer">Mujer</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>FECHA DE NACIMIENTO:</td>
                                        <td><input type="date" name="dob" id="boxx" required/></td>
                                    </tr>
                                    <tr>
                                        <td>No TELÉFONO:</td>
                                        <td><input type="number" name="mobile" id="boxx" maxlength="10" required/></td>
                                    </tr>
                                    <tr>
                                        <td>CORREO (opcional):</td>
                                        <td><input type="email" name="email" id="boxx" placeholder="nombre@dominio.com"/></td>
                                    </tr>
                                    <tr>
                                        <td>FECHA DE INGRESO:</td>
                                        <td><input type="date" name="jdate" id="boxx" required/></td>
                                    </tr>
                                    <tr>
                                        <td>PLAN:</td>
                                        <td>
                                            <select name="plan" id="boxx" required onchange="myplandetail(this.value)">
                                                <option value="">--Favor Seleccionar--</option>
                                                <?php
                                                    $query="select * from plan where active='yes'";
                                                    $result=mysqli_query($con,$query);
                                                    if(mysqli_affected_rows($con)!=0){
                                                        while($row=mysqli_fetch_row($result)){
                                                            echo "<option value=".$row[0].">".$row[1]."</option>";
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>

                                    <tbody id="plandetls"></tbody>

                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>
                                            <input class="a1-btn a1-blue ff-btn ff-btn-primary" type="submit" name="submit" id="submit" value="Registrar">
                                            <input class="a1-btn a1-blue ff-btn ff-btn-secondary" type="reset" name="reset" id="reset" value="Borrar">
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>

        <script>
            function myplandetail(str){
                if(str===""){ document.getElementById("plandetls").innerHTML = ""; return; }
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState === 4 && this.status === 200) {
                        document.getElementById("plandetls").innerHTML = this.responseText;
                    }
                };
                xmlhttp.open("GET","plandetail.php?q="+encodeURIComponent(str),true);
                xmlhttp.send();
            }
        </script>

        <?php include('footer.php'); ?>
    </div>
</div>
</body>
</html>
