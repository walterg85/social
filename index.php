<?php
    // Se inicia el metodo para encapsular todo el contenido de las paginas (bufering), para dar salida al HTML 
    ob_start();
?>

<div class="my-3 p-3 bg-body rounded shadow-sm">
    <h6 class="border-bottom pb-2 mb-0">
        Recent topics
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
    (function () {
        'use strict'

        // Listar las temas
        listTopics();
    })()

    // Listar los temas activos
    function listTopics(){
        $(`#dvContenedorTopics`).html("");

        let _Data = {
            "_method": "_GetAll"
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