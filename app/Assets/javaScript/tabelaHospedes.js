document.querySelectorAll('[role="alert"]').forEach((alert) => {
  window.setTimeout(() => {
    bootstrap.Alert.getOrCreateInstance(alert).close();
  }, 3000);
});

const tabela = new DataTable("#tabelaHospedes", {
  pageLength: 10,
  lengthMenu: [
    [10, 25, 50, -1],
    [10, 25, 50, "todos"],
  ],

  language: {
    search: "Buscar:",
    lengthMenu: "Mostrar _MENU_ registros",
    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
    infoEmpty: "Nenhum registro encontrado",
    zeroRecords: "Nenhum hóspede encontrado",
    paginate: {
      first: "Primeiro",
      last: "Último",
      next: "Próximo",
      previous: "Anterior",
    },
  },
});

document.getElementById("filtroStatus").addEventListener("change", function () {
  const valor = this.value;

  tabela
    .column(5)
    .search(valor ? "^\\s*" + valor + "\\s*$" : "", {
      regex: true,
      smart: false,
    })
    .draw();
});

document.querySelectorAll(".btn-editar-hospede").forEach((button) => {
  button.addEventListener("click", () => {
    const fields = {
      id: "editar_id_cliente",
      nome: "editar_nome_hospede",
      cpf: "editar_cpf_hospede",
      rg: "editar_rg_hospede",
      nascimento: "editar_data_nascimento",
      telefone: "editar_telefone_hospede",
      email: "editar_email_hospede",
      cep: "editar_cep_hospede", //refazer depois esses campos do modal de edição;
      endereco: "editar_endereco_hospede",
      numero: "editar_numero_hospede",
      cidade: "editar_cidade_hospede",
      estado: "editar_estado_hospede",
      pais: "editar_pais_hospede",
      observacoes: "editar_observacoes_hospede",
      status: "editar_status_hospede",
    };

    Object.entries(fields).forEach(([dataName, fieldId]) => {
      document.getElementById(fieldId).value = button.dataset[dataName] || "";
    });
  });
});

document.querySelectorAll(".btn-excluir-hospede").forEach((button) => {
  button.addEventListener("click", () => {
    document.getElementById("excluir_id_cliente").value = button.dataset.id;
    document.getElementById("excluir_nome_hospede").textContent =
      button.dataset.nome;
  });
});

document
  .getElementById("editar_cpf_hospede")
  .addEventListener("input", (event) => {
    const input = event.currentTarget;
    const digits = input.value.replace(/\D/g, "").slice(0, 11);
    input.value = digits.replace(
      /^(\d{3})(\d{3})(\d{3})(\d{0,2}).*/,
      "$1.$2.$3-$4",
    );
  });

const cpfInput = document.getElementById("cpf");

cpfInput.addEventListener("input", () => {
  const digits = cpfInput.value.replace(/\D/g, "").slice(0, 11);
  let formatted = digits;

  if (digits.length > 3) {
    formatted = `${digits.slice(0, 3)}.${digits.slice(3)}`;
  }

  if (digits.length > 6) {
    formatted = `${digits.slice(0, 3)}.${digits.slice(3, 6)}.${digits.slice(6)}`;
  }

  if (digits.length > 9) {
    formatted = `${digits.slice(0, 3)}.${digits.slice(3, 6)}.${digits.slice(6, 9)}-${digits.slice(9)}`;
  }

  cpfInput.value = formatted;
});

const sidebarMenu = document.getElementById("sidebarMenu");

const sidebarToggles = [
  document.getElementById("sidebarToggle"),
  document.getElementById("navbarSidebarToggle"),
].filter(Boolean);

sidebarToggles.forEach((sidebarToggle) => {
  sidebarToggle.addEventListener("click", () => {
    const isCollapsed = sidebarMenu.classList.toggle("sidebar-collapsed");

    sidebarToggles.forEach((toggle) => {
      toggle.setAttribute("aria-expanded", String(!isCollapsed));

      const icon = toggle.querySelector("i");

      if (icon) {
        icon.classList.toggle("bi-chevron-left", !isCollapsed);
        icon.classList.toggle("bi-chevron-right", isCollapsed);
      }
    });
  });
});
