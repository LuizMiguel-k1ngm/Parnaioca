    new DataTable('#tabelaQuartos', {
            paging: true,
            pageLength: 5,
            lengthMenu: [5, 10, 25, 50],
            order: [[1, 'asc']],
            autoWidth: false,
            language: {
                search: 'Buscar:',
                lengthMenu: 'Mostrar _MENU_ registros',
                info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                infoEmpty: 'Nenhum registro encontrado',
                emptyTable: 'Nenhum quarto cadastrado.',
                zeroRecords: 'Nenhum quarto encontrado',
                paginate: { first: 'Primeiro', last: 'Último', next: 'Próximo', previous: 'Anterior' }
            }
        });

        const sidebarMenu = document.getElementById('sidebarMenu');
        const sidebarToggles = [
            document.getElementById('sidebarToggle'),
            document.getElementById('navbarSidebarToggle')
        ].filter(Boolean);

        sidebarToggles.forEach((sidebarToggle) => {
            sidebarToggle.addEventListener('click', () => {
                const isCollapsed = sidebarMenu.classList.toggle('sidebar-collapsed');
                sidebarToggles.forEach((toggle) => {
                    toggle.setAttribute('aria-expanded', String(!isCollapsed));
                    const icon = toggle.querySelector('i');
                    if (icon) {
                        icon.classList.toggle('bi-chevron-left', !isCollapsed);
                        icon.classList.toggle('bi-chevron-right', isCollapsed);
                    }
                });
            });
        });