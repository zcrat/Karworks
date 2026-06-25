let itemsPerPageCS_C_S = 10;
let PageCS = 1;

function PaginatioCustomerServer(elements,paginas) {
    const totalPageCSs = Math.ceil(elements / itemsPerPageCS_C_S);
    const mpagina = Math.floor(paginas / 2);
    let paginationHTML = '';

    PageCS = Math.max(1, Math.min(PageCS, totalPageCSs)); // Asegura que `PageCS` esté en el rango [1, totalPageCSs]

    if (totalPageCSs > 1) {
        // Botón "anterior"
        paginationHTML += (PageCS > 1) ?
            `<button class="pagina" data-pageCS="${PageCS - 1}"><i class="fa-solid fa-left-long"></i></button>` :
            `<button class="paginad" disabled><i class="fa-solid fa-left-long"></i></button>`;

        // Página 1 siempre visible
        paginationHTML += (PageCS === 1) ?
            `<button class="paginaactive" data-pageCS="1">1</button>` :
            `<button class="pagina" data-pageCS="1">1</button>`;

        // Páginas intermedias
        for (let i = 2; i < totalPageCSs; i++) {
            // Si es impar, mantenemos el rango centrado con el mismo número de páginas a cada lado.
            if (paginas % 2 !== 0) {
                if ((PageCS <= mpagina + 1 && i-1 <= paginas) || 
                    (PageCS >= totalPageCSs - mpagina && i >= totalPageCSs - paginas) || 
                    (i >= PageCS - mpagina && i <= PageCS + mpagina)) {
                    paginationHTML += (PageCS === i) ?
                        `<button class="paginaactive" data-pageCS="${i}">${i}</button>` :
                        `<button class="pagina" data-pageCS="${i}">${i}</button>`;
                }
            } 
            // Si es par, el margen debe ajustarse para mostrar una página adicional a la izquierda
            else {
                if ((PageCS <= mpagina + 1 && i-1 <= paginas) || 
                    (PageCS >= totalPageCSs - mpagina && i >= totalPageCSs - paginas) || 
                    (i >= PageCS - mpagina && i < PageCS + mpagina)) {
                    paginationHTML += (PageCS === i) ?
                        `<button class="paginaactive" data-pageCS="${i}">${i}</button>` :
                        `<button class="pagina" data-pageCS="${i}">${i}</button>`;
                }
            }
        }
        

        // Última página siempre visible
        paginationHTML += (PageCS === totalPageCSs) ?
            `<button class="paginaactive" data-pageCS="${totalPageCSs}">${totalPageCSs}</button>` :
            `<button class="pagina" data-pageCS="${totalPageCSs}">${totalPageCSs}</button>`;

        // Botón "siguiente"
        paginationHTML += (PageCS < totalPageCSs) ?
            `<button class="pagina" data-pageCS="${PageCS + 1}"><i class="fa-solid fa-right-long"></i></button>` :
            `<button class="paginad" disabled><i class="fa-solid fa-right-long"></i></button>`;
    }

    $('#pagination_C_S').html(paginationHTML);
}

// Eventos de cambio de página y de elementos por página
$('#pagination_C_S').on('click', '.pagina', function() {
    PageCS = parseInt($(this).data('pageCS'));
    SearchProductos(PageCS);
});
