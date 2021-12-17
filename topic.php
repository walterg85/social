<?php
    // Se inicia el metodo para encapsular todo el contenido de las paginas (bufering), para dar salida al HTML 
    ob_start();
?>

<h3 id="topicName">Sample blog post</h3>
<p class="text-muted"><text id="topicDate">January 1, 2021</text> by <a href="javascript:void(0);" class="text-decoration-none" id="topicOwner">Mark</a></p>

<center>
    <img src="#" class="img-fluid" alt="Topic image" id="topicImage">
</center>

<hr>

<p id="topicContent">...</p>

<hr>

<div>
    <div class="form-floating mb-3">
        <textarea class="form-control" placeholder="Leave a comment here" id="txtComentario" style="height: 100px"></textarea>
        <label for="txtComentario">Comments</label>
    </div>
     <div class="d-md-flex justify-content-md-end">
        <button class="btn btn-success btn-block pull-right" id="btnSendComment">Send comment</button>
    </div>
</div>

<figure class="d-none blockClone">
    <blockquote class="blockquote">
        <p class="fs-6 autor">Name.</p>
    </blockquote>
    <figcaption class="blockquote-footer comentario">
        Coment
    </figcaption>
</figure>
<div id="conetndorComentarios"></div>

<script type="text/javascript">
    let queryString = window.location.search,
        urlParams   = new URLSearchParams(queryString),
        topicId     = urlParams.get('id');

    (function () {
        'use strict'

        // Activar evento para comentar post
        $("#btnSendComment").click( sendComment);

        getTopic();
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
            let data = result.data;
            $("#conetndorComentarios").html("");

            $.each( data, function(index, item){
                let objComent = $(".blockClone").clone();

                objComent.find(".autor").html(`${item.username} | ${item.fecha_registro}`);
                objComent.find(".comentario").html(item.comentario);

                objComent.removeClass("d-none blockClone");

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
            $("#topicImage").attr("src", topic.image);
            $("#topicContent").html(topic.contenido);

            listComents();
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