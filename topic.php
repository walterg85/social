<?php
    // Se inicia el metodo para encapsular todo el contenido de las paginas (bufering), para dar salida al HTML 
    ob_start();
?>

<h3 id="topicName">Sample blog post</h3>
<p class="text-muted"><text id="topicDate">January 1, 2021</text> by <a href="javascript:void(0);" class="text-decoration-none" id="topicOwner">Mark</a></p>
<img src="#" class="img-fluid" alt="Topic image" id="topicImage">
<hr>
<p id="topicContent">...</p>

<script type="text/javascript">
    let queryString = window.location.search,
        urlParams   = new URLSearchParams(queryString),
        topicId     = urlParams.get('id');

    (function () {
        'use strict'

        listTopics();
    })()

    // Buscar el detalle del tema seleccionado
    function listTopics(){
        let _Data = {
            "_method": "_Getunique",
            "topicId": topicId
        };

        $.post(`${base_url}/core/controllers/topic.php`, _Data, function(result){
            let topic = result.data;

            $("#topicName").html(topic.titulo);
            $("#topicDate").html(topic.fecha_registro);
            $("#topicOwner").html(topic.owner);
            // $("#topicImage").html(topic.topicImage);
            $("#topicContent").html(topic.contenido);
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