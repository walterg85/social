<?php
    // Se inicia el metodo para encapsular todo el contenido de las paginas (bufering), para dar salida al HTML 
    ob_start();
?>
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
    <h6 class="border-bottom pb-2 mb-0 labelRecientes">
        Recent topics
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
                topicItem.find(".lblTitulo")
                    .html(item.titulo)
                    .attr("href", `topic.php?id=${item.id}`);
                topicItem.find(".userImg").attr("src", `${base_url}/assets/img/user/${item.userId}.jpg`);

                topicItem.removeClass("d-none topicClone");
                $(topicItem).appendTo(`#dvContenedorTopics`);
            });

            // Listar sugerencias
            listTop();
        });
    }

    // Metodo para traducir la pagina
    function switchPage() {
        $.post(`${base_url}/assets/lang.json`, {}, function(languages) {
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