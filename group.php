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
    
    <small class="d-block text-end mt-3">
        <a href="javascript:void(0);">All updates</a>
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
        if(userData.image != "nothing"){
            initComponent("imgPreviewTopic", "inputPhotoTopic", 900, 400);
            canvasTopic.show();
        } else {
            showAlert("warning", "Before posting a topic, you must update your profile picture", 4000);
        }
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