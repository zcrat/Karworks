

function ShowPaginationGlobal(elements,paginas,itemsPerPage,Page,id) {
    const totalPages = Math.ceil(elements / itemsPerPage);
    const mpagina = Math.floor(paginas / 2);
    let paginationHTML = '';

    Page = Math.max(1, Math.min(Page, totalPages));

    if (totalPages > 1) {
        // Botón "anterior"
        paginationHTML += (Page > 1) ?
            `<button class="pagina" data-page="${Page - 1}"><i class="fa-solid fa-left-long"></i></button>` :
            `<button class="paginad" disabled><i class="fa-solid fa-left-long"></i></button>`;

        // Página 1 siempre visible
        paginationHTML += (Page === 1) ?
            `<button class="paginaactive" data-page="1">1</button>` :
            `<button class="pagina" data-page="1">1</button>`;

        // Páginas intermedias
        for (let i = 2; i < totalPages; i++) {
            // Si es impar, mantenemos el rango centrado con el mismo número de páginas a cada lado.
            if (paginas % 2 !== 0) {
                if ((Page <= mpagina + 1 && i-1 <= paginas) || 
                    (Page >= totalPages - mpagina && i >= totalPages - paginas) || 
                    (i >= Page - mpagina && i <= Page + mpagina)) {
                    paginationHTML += (Page === i) ?
                        `<button class="paginaactive" data-page="${i}">${i}</button>` :
                        `<button class="pagina" data-page="${i}">${i}</button>`;
                }
            } 
            // Si es par, el margen debe ajustarse para mostrar una página adicional a la izquierda
            else {
                if ((Page <= mpagina + 1 && i-1 <= paginas) || 
                    (Page >= totalPages - mpagina && i >= totalPages - paginas) || 
                    (i >= Page - mpagina && i < Page + mpagina)) {
                    paginationHTML += (Page === i) ?
                        `<button class="paginaactive" data-page="${i}">${i}</button>` :
                        `<button class="pagina" data-page="${i}">${i}</button>`;
                }
            }
        }
        
        paginationHTML += (Page === totalPages) ?
            `<button class="paginaactive" data-page="${totalPages}">${totalPages}</button>` :
            `<button class="pagina" data-page="${totalPages}">${totalPages}</button>`;

        paginationHTML += (Page < totalPages) ?
            `<button class="pagina" data-page="${Page + 1}"><i class="fa-solid fa-right-long"></i></button>` :
            `<button class="paginad" disabled><i class="fa-solid fa-right-long"></i></button>`;
    }

    $('#'+id).html(paginationHTML);
}

