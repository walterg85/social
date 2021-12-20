<?php
    // Se inicia el metodo para encapsular todo el contenido de las paginas (bufering), para dar salida al HTML 
    ob_start();
?>

<!-- Panel lateral para registrar nuevo tema -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasTopic" aria-labelledby="offcanvasWithBackdropLabel2"  >
    <div class="offcanvas-header">
        <h5 class="offcanvas-title panelTopicTitulo" id="offcanvasWithBackdropLabel2">Register a new topic here</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="frmTopic" class="needs-validation-topic" novalidate>
            <div class="row">
                <div class="col-12">
                    <div class="mb-3">
                        <label for="inputTitleTopic" class="form-label panelTopicCampo1">Title</label>
                        <input type="text" name="inputTitleTopic" class="form-control" placeholder="Topic name/title" id="inputTitleTopic" autocomplete="off" required>              
                    </div>
                </div>
                <div class="col-12">
                    <div class="mb-3">
                        <label for="inputContentTopic" class="form-label panelTopicCampo2">Content</label>
                        <textarea class="form-control" placeholder="Leave a content here" id="inputContentTopic" name="inputContentTopic" style="height: 120px" required></textarea>
                    </div>
                </div>
            </div>

            <div class="input-group mb-3">
                <label class="input-group-text" for="inputPhotoTopic"><i class="bi bi-camera-fill"></i></label>
                <input type="file" class="form-control" id="inputPhotoTopic" name="inputPhotoTopic">
            </div>

            <button type="button" class="w-100 btn btn-lg btn-success labelBoton" id="btnRegisterTopic">Submit</button>
        </form>
    </div>
</div>

<center>
    <img src="#" class="img-fluid" alt="Topic image" id="groupImage">
</center>

<div class="my-3 p-3 bg-body rounded shadow-sm">
    <h6 class="border-bottom pb-2 mb-0 labelSugerencia">Suggestions</h6>
    <div class="text-muted pt-3 d-none votacionClone">
        <img src="#" width="32" height="32" class="rounded-circle me-2 votacionImg">
        <div class="pb-3 mb-0 small lh-sm border-bottom w-100">
            <a class="votacionName text-decoration-none" href="#" id="linkGroup"></a>
            <span class="d-block votacionOwner">@username</span>
        </div>
    </div>

    <div id="dvContenedorVotacion"></div>

    <small class="d-block text-end mt-3">
        <a href="#">All suggestions</a>
    </small>
</div>

<div class="my-3 p-3 bg-body rounded shadow-sm">
    <h6 class="border-bottom pb-2 mb-0">
        <texto class="labelRecientes">Recent topics</texto>
        <small class="d-block text-end">
            <a href="javascript:void(0);" class="lableNuevotema" id="newTopic">New topic</a>
        </small>
    </h6>

    <div class="d-flex text-muted pt-3 d-none topicClone">
        <img src="#" width="32" height="32" class="rounded-circle me-2 userImg">
        <p class="pb-3 mb-0 small lh-sm border-bottom">
            <strong class="d-block text-gray-dark lblAutor">@username</strong>
            <a class="lblTitulo text-decoration-none" href="#" id="linkGroup">New group</a>
        </p>
    </div>

    <div id="dvContenedorTopics"></div>
    
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

        // Listar los temas
        listTopics();

        // Mostrar detalles del grupo
        getDetail();

        // Validar solo carga tipo fotos
        $('input[type="file"]').change( function(){
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
                topicItem.find(".lblTitulo")
                    .html(item.titulo)
                    .attr("href", `topic.php?id=${item.id}`);
                topicItem.find(".userImg").attr("src", `${base_url}/assets/img/user/${item.userId}.jpg`);

                topicItem.removeClass("d-none topicClone");
                $(topicItem).appendTo(`#dvContenedorTopics`);
            });

            // Listar sugerencias
            listTop(groupId);
        });
    }

    // Buscar los detalles del grupo seleccionado
    function getDetail(){
        let _Data = {
            "_method": "_GetUnique",
            "groupId": groupId
        };

        $.post(`${base_url}/core/controllers/group.php`, _Data, function(result){
            let data = result.data;

            if(data.image != ""){
                $("#groupImage").attr("src", data.image);
            } else {
                $("#groupImage").addClass("d-none");
            }

            $(".generalLabel").html(data.nombre);
            $(".sinceLabel").html(`Since ${data.fecha_registro}`);
        });
    }

    // Metodo para traducir la pagina
    function switchPage() {
        $.post(`${base_url}/assets/lang.json`, {}, function(languages) {
            let panelTopic = languages[lang]["panelTopic"];
            $(`.panelTopicTitulo`).html(panelTopic.panelTopicTitulo);
            $(`.panelTopicCampo1`).html(panelTopic.panelTopicCampo1);
            $(`.panelTopicCampo2`).html(panelTopic.panelTopicCampo2);
            $(`.lableNuevotema`).html(panelTopic.lableNuevotema);

            let lblindex = languages[lang]["index"];
            $(`.labelSugerencia`).html(lblindex.labelSugerencia);
            $(`.labelRecientes`).html(lblindex.labelRecientes);
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