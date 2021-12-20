<?php
    // Se inicia el metodo para encapsular todo el contenido de las paginas (bufering), para dar salida al HTML 
    ob_start();
?>

<h3 id="topicName">Sample blog post</h3>
<p class="text-muted"><text id="topicDate">January 1, 2021</text> <text class="labelBy">by</text> <a href="javascript:void(0);" class="text-decoration-none" id="topicOwner">Mark</a></p>
<button type="button" id="btnILike" class="btn btn-outline-secondary labelLike"><i class="bi bi-heart-fill"></i> I like</button>

<center>
    <img src="#" class="img-fluid" alt="Topic image" id="topicImage" style="height: 300px !important">
</center>

<hr>

<p id="topicContent">...</p>

<hr>

<div id="ctrlComments">
    <div class="form-floating mb-3">
        <textarea class="form-control" placeholder="Leave a comment here" id="txtComentario" style="height: 100px"></textarea>
        <label for="txtComentario" class="labelComentario">Comments</label>
    </div>
     <div class="d-md-flex justify-content-md-end">
        <button class="btn btn-success btn-block pull-right labelEnviarcomentario" id="btnSendComment">Send comment</button>
    </div>
</div>

<div class="flex-row mb-3 d-none blockClone">
    <div class="p-2"><img src="#" width="32" height="32" class="rounded-circle me-2 userImg"></div>
    <div class="p-2">
        <figure class="mb-1">
            <blockquote class="blockquote">
                <p class="fs-6 autor">Name.</p>
            </blockquote>
            <figcaption class="blockquote-footer comentario mb-1">
                Coment
            </figcaption>
        </figure>
        <ul class="list-inline">
            <li class="list-inline-item"><a class="text-decoration-none d-none linkResponder" href="javascript:void(0);">Responder</a></li>
            <li class="list-inline-item"><a class="text-decoration-none d-none linkEditar" href="javascript:void(0);">Editar</a></li>
            <li class="list-inline-item"><a class="text-decoration-none d-none linkBorrar" href="javascript:void(0);">Borrar</a></li>
        </ul>
    </div>
</div>


<div class="row ">
    <div class="col-md-1">
        
    </div>
    <div class="col-md-11">
        
    </div>
</div>
<div id="conetndorComentarios" class="mb-5"></div>

<script type="text/javascript">
    let queryString = window.location.search,
        urlParams   = new URLSearchParams(queryString),
        topicId     = urlParams.get('id'),
        labelLike   = "";

    (function () {
        'use strict'

        // Activar evento para comentar post
        $("#btnSendComment").click( sendComment);

        getTopic();
        preventTopic();

        let tmpCanvasuser = document.getElementById('offcanvasUser')
        tmpCanvasuser.addEventListener('hidden.bs.offcanvas', preventTopic);

        $("#btnILike").click( fnSetLike);
    })()

    // Enviar comentarios del post
    function sendComment(){
        let comentario = $("#txtComentario").val();

        if(comentario.length == 0)
            return false;

        let _Data = {
            "_method": "_Setcoments",
            "comentario": comentario,
            "topicId": topicId
        };

        $.post(`${base_url}/core/controllers/topic.php`, _Data, function(result){
            $("#txtComentario").val("");
            listComents();
        });
    }

    // Buscar los comentarios del tema actual
    function listComents(){
        let _Data = {
            "_method": "_GetComments",
            "topicId": topicId
        };

        $.post(`${base_url}/core/controllers/topic.php`, _Data, function(result){
            $("#conetndorComentarios").html("");

            let data = result.data,
                esValido = true;

            if(isLoged == 0 || userData.image == "nothing")
                esValido = false;

            $.each( data, function(index, item){
                let objComent = $(".blockClone").clone();

                objComent.find(".autor").html(`${item.username} | <small>${item.fecha_registro}</small>`);
                objComent.find(".comentario").html(item.comentario);
                objComent.find(".userImg").attr("src", item.userFoto);

                if(esValido)
                    objComent.find(".linkResponder").removeClass("d-none");

                if(item.user_id == userData.id)
                    objComent.find(".linkEditar, .linkBorrar").removeClass("d-none");

                objComent.removeClass("d-none blockClone");
                objComent.addClass("d-flex");

                $(objComent).appendTo("#conetndorComentarios");
            });
        });
    }

    // Buscar el detalle del tema seleccionado
    function getTopic(){
        let _Data = {
            "_method": "_Getunique",
            "topicId": topicId
        };

        $.post(`${base_url}/core/controllers/topic.php`, _Data, function(result){
            let topic = result.data;

            $("#topicName").html(topic.titulo);
            $("#topicDate").html(topic.fecha_registro);
            $("#topicOwner").html(topic.owner);

            if(topic.image == ""){
                $("#topicImage").addClass("d-none");
            } else{
                $("#topicImage").attr("src", topic.image);
            }

            $("#topicContent").html(topic.contenido);

            listComents();
        });
    }

    // Metodo para validar la foto de perfil antes de comentar un tema
    function preventTopic(){
        if(isLoged == 0){
            $("#ctrlComments").addClass("d-none");
            $("#btnILike").addClass("d-none");
        } else{
            if(userData.image == "nothing"){
                $("#txtComentario")
                    .val("Before comment a topic, you must update your profile picture")
                    .attr("disabled", "disabled");

                $("#btnSendComment").attr("disabled", "disabled");
            } else {
                $("#txtComentario")
                    .val("")
                    .removeAttr("disabled");

                $("#btnSendComment").removeAttr("disabled");
                listComents();
            }
        }
    }

    // Metodo para consultar si el usuario actual ya ha dado like al post actual
    function getLikes(){
        let _Data = {
            "_method": "_GetLikes",
            "topicId": topicId
        };

        $.post(`${base_url}/core/controllers/topic.php`, _Data, function(result){
            if(result.data.existe == 1){
                $("#btnILike")
                    .html(`<i class="bi bi-heart-fill text-danger"></i> ${labelLike}`)
                    .unbind();
            } else {
                $("#btnILike").html(`<i class="bi bi-heart-fill"></i> ${labelLike}`);
            }
        });
    }

    // Metodo para enviar like al post actual
    function fnSetLike(){
        let _Data = {
            "_method": "_SetLikes",
            "topicId": topicId
        };

        $.post(`${base_url}/core/controllers/topic.php`, _Data, function(result){
            getLikes();
        });
    }

    // Metodo para traducir la pagina
    function switchPage() {
        $.post(`${base_url}/assets/lang.json`, {}, function(languages) {
            let panelTopic = languages[lang]["topic"];
            $(`.labelBy`).html(panelTopic.labelBy);
            labelLike = panelTopic.labelLike;
            $(`.labelComentario`).html(panelTopic.labelComentario);
            $(`.labelEnviarcomentario`).html(panelTopic.labelEnviarcomentario);

            getLikes();
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