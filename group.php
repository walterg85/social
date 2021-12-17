<?php
    // Se inicia el metodo para encapsular todo el contenido de las paginas (bufering), para dar salida al HTML 
    ob_start();
?>

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
                    <img src="#" class="figure-img img-fluid rounded imgPreviewTopic">
                    <figcaption class="figure-caption text-end labelImg">Preview</figcaption>
                </figure>
            </center>

            <div class="input-group mb-3">
                <label class="input-group-text" for="inputPhoto"><i class="bi bi-camera-fill"></i></label>
                <input type="file" class="form-control" id="inputPhotoTopic">
            </div>

            <button type="button" class="w-100 btn btn-lg btn-success" id="btnRegisterTopic">Submit</button>
        </form>
    </div>
</div>

<div class="my-3 p-3 bg-body rounded shadow-sm">
    <h6 class="border-bottom pb-2 mb-0">
        Recent topics
        <small class="d-block text-end">
            <a href="javascript:void(0);" id="newTopic">New topic</a>
        </small>
    </h6>

    <div class="d-flex text-muted pt-3 d-none topicClone">
        <img src="#" width="32" height="32" class="rounded-circle me-2 userImg">

        <p class="pb-3 mb-0 small lh-sm border-bottom">
            <strong class="d-block text-gray-dark lblAutor">@username</strong>
            <texto class="lblTitulo"></texto>
        </p>
    </div>

    <div id="dvContenedorTopics"></div>
    
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

<script type="text/javascript">
    let queryString = window.location.search,
        urlParams   = new URLSearchParams(queryString),
        groupId     = urlParams.get('id'),
        canvasTopic = new bootstrap.Offcanvas( $("#offcanvasTopic") );

    (function () {
        'use strict'

        // Icia verificacion de foto de perfila
        $("#newTopic").click( preventTopic);

        // Disparar evento para registrar tema
        $("#btnRegisterTopic").click( createTopic);

        // Listar las temas
        listTopics();
    })()

    // Metodo para actualizar perfil de usuario
    function createTopic(){
        let forms = document.querySelectorAll('.needs-validation-topic'),
            continuar = true;

        Array.prototype.slice.call(forms).forEach(function (formv){ 
            if (!formv.checkValidity())
                continuar = false;

            formv.classList.add('was-validated');
        });

        if(!continuar)
            return false;

        let form = $("#frmTopic")[0],
            formData = new FormData(form);

        formData.append("_method", "POST");
        formData.append("groupId", groupId);

        if(cropImage)
            formData.append("cropImage", cropImage);

        $.ajax({
            url: `${base_url}/core/controllers/topic.php`,
            data: formData,
            type: 'POST',
            success: function(response){
                if(response.codeResponse == 200){
                    $("#frmTopic").removeClass("was-validated");
                    $("#frmTopic")[0].reset();
                    canvasTopic.hide();
                    showAlert("success", "Registered topic");
                    listTopics();
                }else{
                    showAlert("error", "An error has occurred");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                showAlert("error", "An error has occurred");
            },
            cache: false,
            contentType: false,
            processData: false
        });
    }

    // Metodo para validar la foto de perfil antes de crear un nuevo tema
    function preventTopic(){
        if(isLoged == 0){
            window.location.replace("login.html");
        } else{
            if(userData.image != "nothing"){
                initComponent("imgPreviewTopic", "inputPhotoTopic", 900, 400);
                canvasTopic.show();
            } else {
                showAlert("warning", "Before posting a topic, you must update your profile picture", 4000);
            }
        }        
    }

    // Listar los temas activos
    function listTopics(){
        $(`#dvContenedorTopics`).html("");

        let _Data = {
            "_method": "GET",
            "groupId": groupId
        };

        $.post(`${base_url}/core/controllers/topic.php`, _Data, function(result){
            $.each( result.data, function(index, item){
                let topicItem = $(".topicClone").clone();

                topicItem.find(".lblAutor").html(item.username);
                topicItem.find(".lblTitulo").html(item.titulo);
                topicItem.find(".userImg").attr("src", `${base_url}/assets/img/user/${item.userId}.jpg`);

                topicItem.removeClass("d-none topicClone");
                $(topicItem).appendTo(`#dvContenedorTopics`);
            });
        });
    }
</script>




<?php
    // Se obtiene el contenido del bufer
    $content = ob_get_contents();

    // Limpiar el bufer para liberar
    ob_end_clean();

    // Se carga la pagina maestra para imprimir la pagina global
    include("masterPage.php");
?>