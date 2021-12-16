<?php
    session_start();
    $loged = TRUE;

    if(!isset($_SESSION['socialLogin']))
        $loged = FALSE;
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <title>Offcanvas navbar template · Bootstrap v5.0</title>

    <!-- Bootstrap, CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- Bootstrap icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.6.1/font/bootstrap-icons.css">

    <!-- sweetalert2 -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- cropperCSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.9/cropper.min.css" integrity="sha512-w+u2vZqMNUVngx+0GVZYM21Qm093kAexjueWOv9e9nIeYJb1iEfiHC7Y+VvmP/tviQyA5IR32mwN/5hTEJx6Ng==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- cropperJS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.9/cropper.min.js" integrity="sha512-9pGiHYK23sqK5Zm0oF45sNBAX/JqbZEP7bSDHyt+nT3GddF+VFIcYNqREt0GDpmFVZI3LZ17Zu9nMMc9iktkCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="https://getbootstrap.com/docs/5.0/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
    <link rel="icon" href="https://getbootstrap.com/docs/5.0/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="https://getbootstrap.com/docs/5.0/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
    <link rel="manifest" href="https://getbootstrap.com/docs/5.0/assets/img/favicons/manifest.json">
    <link rel="mask-icon" href="https://getbootstrap.com/docs/5.0/assets/img/favicons/safari-pinned-tab.svg" color="#7952b3">
    <link rel="icon" href="https://getbootstrap.com/docs/5.0/assets/img/favicons/favicon.ico">
    <meta name="theme-color" content="#7952b3">

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        .offcanvas-end{
            width: 500px !important;
        }
    </style>

    <!-- Custom styles for this template -->
    <link href="https://getbootstrap.com/docs/5.0/examples/offcanvas-navbar/offcanvas.css" rel="stylesheet">
</head>
<body class="bg-light">
    <!-- Modal para editar las imagenes -->
    <div class="modal fade" id="modalCrop" tabindex="-1" role="dialog" aria-labelledby="modalLabelP" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabelP">Edit / Crop the image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="img-container mb-3" style="max-height: 500px">
                        <img id="previewCrop" src="#">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btnmdlC" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="cropImage">Apply</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel lateral para registrar nuevo grupo -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasGroup" aria-labelledby="offcanvasWithBackdropLabel"  >
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasWithBackdropLabel">Register a new group here</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form id="frmGroup" class="needs-validation-group" novalidate>
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="inputNameGroup" class="form-label">Name</label>
                            <input type="text" name="inputNameGroup" class="form-control" id="inputNameGroup" autocomplete="off" required>              
                        </div>
                    </div>
                </div>
                <button type="button" class="w-100 btn btn-lg btn-success" id="btnRegisterGroup">Submit</button>
            </form>
        </div>
    </div>

    <!-- Panel lateral para registrar nuevo tema -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasTopic" aria-labelledby="offcanvasWithBackdropLabel2"  >
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasWithBackdropLabel2">Register a new topic here</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form id="frmTopic" class="needs-validation-topic" novalidate>
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="inputTitleTopic" class="form-label">Title</label>
                            <input type="text" name="inputTitleTopic" class="form-control" placeholder="Topic name/title" id="inputTitleTopic" autocomplete="off" required>              
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="inputContentTopic" class="form-label">Content</label>
                            <textarea class="form-control" placeholder="Leave a content here" id="inputContentTopic" name="inputContentTopic" style="height: 120px" required></textarea>
                        </div>
                    </div>
                </div>

                <center>
                    <figure class="figure d-none" id="imgPreview">
                        <img src="#" class="figure-img img-fluid rounded imgPreview">
                        <figcaption class="figure-caption text-end labelImg">Preview</figcaption>
                    </figure>
                </center>

                <div class="input-group mb-3">
                    <label class="input-group-text" for="inputPhoto"><i class="bi bi-camera-fill"></i></label>
                    <input type="file" class="form-control" id="inputPhoto">
                </div>

                <button type="button" class="w-100 btn btn-lg btn-success" id="btnRegisterTopic">Submit</button>
            </form>
        </div>
    </div>

    <!-- Menu principal -->
    <nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark" aria-label="Main navigation">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Offcanvas navbar</a>
            <button class="navbar-toggler p-0 border-0" type="button" id="navbarSideCollapse" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Notifications</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Switch account</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-expanded="false">Settings</a>
                        <ul class="dropdown-menu" aria-labelledby="dropdown01">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </li>
                </ul>
                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Grupos -->
    <div class="nav-scroller bg-body shadow-sm">
        <nav class="nav nav-underline" aria-label="Secondary navigation">
            <a class="nav-link active" aria-current="page" href="javascript:void(0);" data-bs-toggle="offcanvas" data-bs-target="#offcanvasGroup" aria-controls="offcanvasGroup">New group</a>
            <label class="nav-link">|</label>
            <div id="dvGroupContent" class="nav nav-underline"></div>
        </nav>
    </div>

    <!-- Contenido principal -->
    <main class="container">
        <div class="d-flex align-items-center p-3 my-3 text-white bg-purple rounded shadow-sm">
            <img class="me-3" src="https://getbootstrap.com/docs/5.0/assets/brand/bootstrap-logo-white.svg" alt="" width="48" height="38">
            <div class="lh-1">
                <h1 class="h6 mb-0 text-white lh-1">Bootstrap</h1>
                <small>Since 2011</small>
            </div>
        </div>

        <!-- Lista de temas recientes -->
        <div class="d-flex text-muted pt-3 d-none topicClone">
            <svg class="bd-placeholder-img flex-shrink-0 me-2 rounded" width="32" height="32" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 32x32" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#007bff"/><text x="50%" y="50%" fill="#007bff" dy=".3em">32x32</text></svg>

            <p class="pb-3 mb-0 small lh-sm border-bottom">
                <strong class="d-block text-gray-dark">@username</strong>
                Some representative placeholder content, with some information about this user. Imagine this being some sort of status update, perhaps?
            </p>
        </div>
        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <h6 class="border-bottom pb-2 mb-0">
                Recent topics
                <small class="d-block text-end">
                    <a href="javascript:void(0);" id="newTopic">New topic</a>
                </small>
            </h6>
            
            <small class="d-block text-end mt-3">
                <a href="javascript:void(0);">All updates</a>
            </small>
        </div>

        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <h6 class="border-bottom pb-2 mb-0">Suggestions</h6>
            <div class="d-flex text-muted pt-3">
                <svg class="bd-placeholder-img flex-shrink-0 me-2 rounded" width="32" height="32" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 32x32" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#007bff"/><text x="50%" y="50%" fill="#007bff" dy=".3em">32x32</text></svg>

                <div class="pb-3 mb-0 small lh-sm border-bottom w-100">
                <div class="d-flex justify-content-between">
                <strong class="text-gray-dark">Full Name</strong>
                <a href="#">Follow</a>
                </div>
                <span class="d-block">@username</span>
                </div>
            </div>
            <div class="d-flex text-muted pt-3">
                <svg class="bd-placeholder-img flex-shrink-0 me-2 rounded" width="32" height="32" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 32x32" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#007bff"/><text x="50%" y="50%" fill="#007bff" dy=".3em">32x32</text></svg>

                <div class="pb-3 mb-0 small lh-sm border-bottom w-100">
                    <div class="d-flex justify-content-between">
                        <strong class="text-gray-dark">Full Name</strong>
                        <a href="#">Follow</a>
                    </div>
                    <span class="d-block">@username</span>
                </div>
            </div>
            <div class="d-flex text-muted pt-3">
                <svg class="bd-placeholder-img flex-shrink-0 me-2 rounded" width="32" height="32" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 32x32" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#007bff"/><text x="50%" y="50%" fill="#007bff" dy=".3em">32x32</text></svg>

                <div class="pb-3 mb-0 small lh-sm border-bottom w-100">
                    <div class="d-flex justify-content-between">
                        <strong class="text-gray-dark">Full Name</strong>
                        <a href="#">Follow</a>
                    </div>
                    <span class="d-block">@username</span>
                </div>
            </div>
            <small class="d-block text-end mt-3">
                <a href="#">All suggestions</a>
            </small>
        </div>
    </main>


    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script type="text/javascript">
        var canvasGroup         = new bootstrap.Offcanvas( $("#offcanvasGroup") ),
            canvasTopic         = new bootstrap.Offcanvas( $("#offcanvasTopic") )
            maxCroppedWidth     = 300,
            maxCroppedHeight    = 300,
            topicImage          = null,
            userData            = <?php echo json_encode($_SESSION['authData']); ?>;

        (function () {
            'use strict'

            document.querySelector('#navbarSideCollapse').addEventListener('click', function () {
                document.querySelector('.offcanvas-collapse').classList.toggle('open')
            });

            $("#btnRegisterGroup").click( fnRegistrarGrupo);

            // Mostrar los 1ros 10 gupos en la barra principal
            listGroup(10, "dvGroupContent");

            // Activar control de edicion de imagenes
            initComponent();

            // Icia verificacion de foto de perfila
            $("#newTopic").click( preventTopic);
        })()

        // Metodo para registrar nuevo grupo
        function fnRegistrarGrupo() {
            let forms       = document.querySelectorAll('.needs-validation-group'),
                continuar   = true;

            Array.prototype.slice.call(forms).forEach(function (formv) { 
                if (!formv.checkValidity())
                    continuar = false;

                formv.classList.add('was-validated');
            });

            if(!continuar)
                return false;

            let _Data = {
                "_method": "POST",
                "nombre": $("#inputNameGroup").val()
            };

            $.post("core/controllers/group.php", _Data, function(result){
                if(result.codeResponse == 200){
                    showAlert("success", "Group registered successfully.");
                    canvasGroup.hide();

                    // Limpiar formulario
                    $("#frmGroup").removeClass("was-validated");
                    $("#inputNameGroup").val("");
                    
                    // Mostrar los 1ros 10 gupos en la barra principal
                    listGroup(10, "dvGroupContent");
                } else {
                    showAlert("warning", "Group not registered.");
                }
            });
        }

        // Metodo para listar grupos
        function listGroup(limit, contenedor) {
            $(`#${contenedor}`).html("");

            let _Data = {
                "_method": "GET",
                "limit": limit
            };

            let links = "";
            $.post("core/controllers/group.php", _Data, function(result){
                $.each( result.data, function(index, item){
                    links += `<a class="nav-link" href="javascript:void(0);">${item.nombre}</a>`;
                });
                $(links).appendTo(`#${contenedor}`);
            });
        }

        // Metodo para mostrar una alerta de notificaicon
        // icon: success || error || info
        // text: texto que se mostrara en pantalla
        function showAlert(icon, text, time = 3000){
            Swal.fire({
                position: 'top-end',
                icon: icon,
                text: text,
                showConfirmButton: false,
                timer: time
            });
        }

        // Iniciar componenetes para edicion de imagen
        function initComponent() {
            // Controlar tipo de objeto que intentan subir
            $('input[type="file"]').unbind().change( function(){
                let ext = $( this ).val().split('.').pop();

                if ($( this ).val() != ''){
                    if($.inArray(ext, ["jpg", "jpeg", "png", "bmp", "raw", "tiff"]) != -1){
                        if($(this)[0].files[0].size > 5242880){
                            $( this ).val('');
                            showAlert("warning", "Your selected file is larger than 5MB");
                        }
                    }else{
                        $( this ).val('');
                        showAlert("warning", `${ext} files not allowed, only images`);
                    }
                }
            });

            // Image Cropper
            let picture = $(".imgPreview"),
                image       = $("#previewCrop")[0],
                inputFile1   = $("#inputPhoto")[0],
                $modal      = $('#modalCrop'),
                cropper     = null;

            inputFile1.addEventListener("change", function(e){
                let files = e.target.files,
                    done  = function (url){
                        inputFile1.value = "";
                        image.src = url;
                        $modal.modal('show');
                    },
                    reader,
                    file,
                    url;

                if (files && files.length > 0){
                    file = files[0];

                    if (URL){
                        done(URL.createObjectURL(file));
                    }
                    else if (FileReader){
                        reader = new FileReader();
                        reader.onload = function(e){
                            done(reader.result);
                        };
                        reader.readAsDataURL(file);
                    }
                }
            });

            $modal.unbind().on('shown.bs.modal', function(){
                let URL         = window.URL || window.webkitURL,
                    container   = document.querySelector('.img-container'),
                    download    = document.getElementById('download'),
                    actions     = document.getElementById('cropper-buttons'),
                    options     = {
                        viewMode: 1,
                        aspectRatio: maxCroppedWidth / maxCroppedHeight,
                        background: false
                    };

                cropper = new Cropper(image, options);
            }).on('hidden.bs.modal', function(){
                cropper.destroy();
                cropper = null;
            });

            $("#cropImage").unbind().click( function(){
                let canvas;

                $modal.modal("hide");

                if(cropper){
                    canvas = cropper.getCroppedCanvas({
                        width: maxCroppedWidth,
                        height: maxCroppedHeight,
                    });

                    picture
                        .attr("src", canvas.toDataURL())
                        .parent().removeClass('d-none');

                    canvas.toBlob(function (blob){
                        topicImage = blob;
                    });
                }
            });
        }

        // Metodo para validar la foto de perfil antes de crear un nuevo tema
        function preventTopic(){
            if(userData.image != "nothing"){
                canvasTopic.show();
            } else {
                showAlert("warning", "Before posting a topic, you must update your profile picture", 4000);
            }
        }
    </script>
  </body>
</html>
