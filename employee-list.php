<?php include_once 'services/verificar-token.php'; $status = isset($_GET['status']) ? $_GET['status'] : true?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Authors -->
    <meta name="author" content="Caique Patelli Scapeline" />
    <meta name="author" content="Gianluca Dias De Micheli" />

    <title>TGS | Funcionários</title>

    <!-- Shortcut Icon -->
    <link rel="shortcut icon" href="assets/icons/logo_bg.svg" />

    <!-- Custom Fonts -->
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="assets/css/sb-admin-2.css" rel="stylesheet">
    <link href="assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- Sweet Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.8/dist/sweetalert2.all.min.js"></script>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon">
                    <img src="assets/img/logo.png" height="50px">
                </div>
                <div class="sidebar-brand-text mx-3">TGS</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <?php
                $json = file_get_contents('mock/sidebarItems.json');
                $data = json_decode($json);

                foreach ($data as $key => $value){
                    $workaround = isset($value->target) ? "target='" . $value->target . "' href='" . $value->link . "'" : "href='" . $value->link . ".php'";
                    echo "<li class='nav-item'>
                                <a class='nav-link' " . $workaround . ">
                                    <img src='assets/icons/" . $value->icon . ".svg' width='18px' height='18px'/>
                                    <span>" . $value->title . "</span></a>
                            </li>";
                }
            ?>
            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $_SESSION['name'] ?></span>
                                <img class="img-profile rounded-circle"
                                    src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Funcionários</h1>
                        <div class="col-auto">
                            <a href="employee-form.php" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                                <i class="fas fa-plus fa-sm text-white"></i> 
                                Add Funcionário
                            </a>
                        </div>
                    </div>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="d-flex card-header py-3 justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Listagem de Funcionários</h6>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="disabledProcedures" onchange="status()" <?= isset($_GET['status']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="disabledProcedures">
                                    Exibir funcionários não ativos
                                </label>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Sobrenome</th>
                                            <th>Email</th>
                                            <th class="text-center" style="width:150px">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            include_once 'services/requestAPI.php';
                                            $json = requestApi('GET', 'http:/localhost:8080/employees/list/' . $status, false, $_SESSION['token']);
                                            $data = json_decode($json);

                                            foreach ($data as $key => $value){
                                                if ($value->status){
                                                    $workaround = "<a href='#' class='btn btn-sm btn-danger' data-toggle='modal' data-target='#removeModal" . $value->userId . "'><i class='fas fa-trash'></i></a>";
                                                } else {
                                                    $workaround = "";
                                                }
                                                echo "<tr>
                                                        <td> " . $value->name . " </td>
                                                        <td> " . $value->surname . " </td>
                                                        <td> " . $value->email . " </td>
                                                        <td class='text-center'> 
                                                            <a href='#' class='btn btn-sm btn-primary' data-toggle='modal' data-target='#detailModal" . $value->userId . "'><i class='fas fa-eye'></i></a>
                                                            <a href='employee-edit.php?id=" . $value->userId . "&name=" . $value->name . "&surname=" . $value->surname . "&document=" . $value->document . "&email=" . $value->email . "&telephone=" . $value->telephone . "&cellphone=" . $value->cellphone . "' class='btn btn-sm btn-warning'><i class='fas fa-pen'></i></a>
                                                            " . $workaround . "
                                                        </td>
                                                    </tr>";
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; TGS 2021</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tem certeza que quer sair?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Selecione "Logout" se estiver preparado para sair.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-primary" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Modal-->
    <?php
        include_once 'services/requestAPI.php';
        $json = requestApi('GET', 'http:/localhost:8080/employees/list/' . $status, false, $_SESSION['token']);
        $data = json_decode($json);

        foreach ($data as $key => $value){
            echo "
                <div class='modal fade' id='detailModal" . $value->userId . "' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel'
                    aria-hidden='true'>
                    <div class='modal-dialog' role='document'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h5 class='modal-title' id='exampleModalLabel'>Detalhes do Funcionário</h5>
                                <button class='close' type='button' data-dismiss='modal' aria-label='Close'>
                                    <span aria-hidden='true'>×</span>
                                </button>
                            </div>
                            <div class='modal-body'>
                                <form class='row g-3'>

                                <div class='mb-3 col-6 col-md-6'>
                                    <label for='name' class='form-label'>Nome</label>
                                    <input type='text' class='form-control' id='name' value='" . $value->name . "' disabled>
                                </div>
                                <div class='mb-3 col-6 col-md-6'>
                                    <label for='surname' class='form-label'>Sobrenome</label>
                                    <input type='text' class='form-control' id='surname' value='" . $value->surname . "' disabled>
                                </div>
                                <div class='mb-3 col-12 col-md-12'>
                                    <label for='document' class='form-label'>CPF</label>
                                    <input type='text' class='form-control' id='document' value='" . $value->document . "' disabled>
                                </div>
                                <div class='mb-3 col-12 col-md-12'>
                                    <label for='email' class='form-label'>Email</label>
                                    <input type='email' class='form-control' id='email' value='" . $value->email . "' disabled>
                                </div>
                                <div class='mb-3 col-12 col-md-6'>
                                    <label for='telephone' class='form-label'>Telefone</label>
                                    <input type='text' class='form-control' id='telephone' value='" . $value->telephone . "' disabled>
                                </div>
                                <div class='mb-3 col-12 col-md-6'>
                                    <label for='cellphone' class='form-label'>Celular</label>
                                    <input type='text' class='form-control' id='cellphone' value='" . $value->cellphone . "' disabled>
                                </div>
                                </form>
                            </div>
                            <div class='modal-footer'>
                                <a href='employee-edit.php?id=" . $value->userId . "&name=" . $value->name . "&surname=" . $value->surname . "&document=" . $value->document . "&email=" . $value->email . "&telephone=" . $value->telephone . "&cellphone=" . $value->cellphone . "' class='btn btn-primary'>Editar</a>
                            </div>
                        </div>
                    </div>
                </div>";
        }
    ?>

    <!-- Remove Modal-->
    <?php
        include_once 'services/requestAPI.php';
        $json = requestApi('GET', 'http:/localhost:8080/employees/list/' . $status, false, $_SESSION['token']);
        $data = json_decode($json);

        foreach ($data as $key => $value){
            echo "
                <div class='modal fade' id='removeModal" . $value->userId . "' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel'
                    aria-hidden='true'>
                    <div class='modal-dialog' role='document'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h5 class='modal-title' id='exampleModalLabel'>Tem certeza que quer exluir esse registro?</h5>
                                <button class='close' type='button' data-dismiss='modal' aria-label='Close'>
                                    <span aria-hidden='true'>×</span>
                                </button>
                            </div>
                            <div class='modal-body'>Selecione 'Sim' para confirmar a exclusão.</div>
                            <div class='modal-footer'>
                                <form action='' method='post'>
                                    <button class='btn btn-secondary' type='button' data-dismiss='modal'>Não</button>
                                    <button class='btn btn-primary' type='submit' name='confirmDeletion'>Sim</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>";
        }
        if (isset($_POST['confirmDeletion'])){
            $postData = array (
                "userId" => $value->userId,
                "document" => $value->userId,
                "name" => $value->name,
                "surname" => $value->surname,
                "email" => $value->email,
                "telephone" => $value->telephone,
                "cellphone" => $value->cellphone,
                "expertise" => $value->expertise,
                "password" => $value->password,
            );

            $response = requestApi('POST', 'http:/localhost:8080/employees/remove', $postData, $_SESSION['token']);

            echo "<script> window.location = 'employee-list.php?response=" . $response . "' </script>";
        }
    ?>

    <!-- Bootstrap core JavaScript-->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="assets/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>    

    <!-- Page level custom scripts -->
    <script src="assets/js/demo/datatables-demo.js"></script>

    <script>
        function status(){
            if (<?= $status ?> == false){
                window.location='employee-list.php';
            } else {
                window.location='employee-list.php?status=false';
            }
        }
    </script>

</body>

</html>

<?php

    $response = isset($_GET['response']) ? $_GET['response'] : null;

    if (isset($response)){
        if ($response == '200 OK'){
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: 'Operação realizada com sucesso!',
                    confirmButtonUrl: 'employee-list.php'
                })
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Algo deu errado ao cadastrar o procedimento!',
                    confirmButtonUrl: 'employee-list.php'
                })
            </script>";
        }
    }

?>