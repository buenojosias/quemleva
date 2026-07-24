<?php

use Livewire\Component;

new class extends Component
{
    /*
        Este componente deve ser carregado apenas com o $campaign no mount().
        Ao clicar no botão #open-promises (em items-table.blade.php), deve abrir o slide e carregar as promessas de doação relacionadas ao item selecionado.
        Exibir: Nome do doador, Quantidade prometida, Status
        Caso o status seja "pendente", exibir um botão para confirmar a promessa. Ao clicar no botão, deve atualizar o status para "promised" e exibir uma mensagem de sucesso.
        Também exibir um botão para marcar como recebido. Ao clicar no botão, deve atualizar o status para "received" e exibir uma mensagem de sucesso.
        Por fim, exibir um botão para excluir a promessa. Ao clicar no botão, deve exibir uma mensagem de confirmação. Caso o usuário confirme, deve excluir a promessa e exibir uma mensagem de sucesso.
        Priorize os componentes do TallStackUI.
    */

    public $slide = false;
};