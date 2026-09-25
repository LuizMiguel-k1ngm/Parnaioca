const cepInput = document.getElementById('cep');
        const buscarCepButton = document.getElementById('buscarCep');
        const cepFeedback = document.getElementById('cepFeedback');

        const formatarCep = (value) => {
            const digits = value.replace(/\D/g, '').slice(0, 8);
            return digits.length > 5
                ? `${digits.slice(0, 5)}-${digits.slice(5)}`
                : digits;
        };

        const buscarCep = async () => {
            const cep = cepInput.value.replace(/\D/g, '');

            cepInput.value = formatarCep(cep);

            if (cep.length !== 8) {
                cepFeedback.textContent = 'Digite um CEP válido com 8 dígitos.';
                cepFeedback.className = 'form-text text-danger';
                cepInput.focus();
                return;
            }

            buscarCepButton.disabled = true;
            cepFeedback.textContent = 'Buscando endereço...';
            cepFeedback.className = 'form-text text-secondary';

            try {
                const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);

                if (!response.ok) {
                    throw new Error('Falha na consulta');
                }

                const data = await response.json();

                if (data.erro) {
                    throw new Error('CEP não encontrado');
                }

                document.getElementById('endereco').value = data.logradouro || '';
                document.getElementById('cidade').value = data.localidade || '';
                document.getElementById('estado').value = data.uf || '';

                cepFeedback.textContent = 'Endereço preenchido com sucesso.';
                cepFeedback.className = 'form-text text-success';
            } catch (error) {
                cepFeedback.textContent = error.message === 'CEP não encontrado'
                    ? error.message
                    : 'Não foi possível consultar o CEP. Tente novamente.';
                cepFeedback.className = 'form-text text-danger';
            } finally {
                buscarCepButton.disabled = false;
            }
        };

        cepInput.addEventListener('input', () => {
            cepInput.value = formatarCep(cepInput.value);
        });

        cepInput.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                event.preventDefault();
                buscarCep();
            }
        });

        buscarCepButton.addEventListener('click', buscarCep);