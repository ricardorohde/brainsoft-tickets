<?php
include ("../../utils/api-chat/api-last-chats.php");
?>

<div class="card-header">
    <h5 class="mb-0" style="padding: 0.65rem 0.75rem; font-size: 1rem;">Chats em atendimento</h5>
</div>
<div class="card-body">
    <div class="form-group row">
        <div class="table-responsive" style="overflow-x: hidden;">
            <table class="table table-striped last-tickets">
                <thead>
                <tr>
                    <th>Chat</th>
                    <th>Atendente</th>
                    <th>Cliente</th>
                    <th>Iniciado ás</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $actual_date = $day . "/" . $month . "/" . $year;
                foreach ($data1 as $key => $value):
                    $date_formated = date('d/m/Y', strtotime($value->chat_inicio));
                    if($value->chat_final == null && $value->chat_atendente == "fernando"):
                        $chat_started = date('H:m:s', strtotime($value->chat_inicio));
                        ?>
                        <tr>
                            <td><a href="#" class="chat-code"><?= $value->cod_chat ?></a></td>
                            <td><?= ucfirst($value->chat_atendente) ?></td>
                            <td><?= ucfirst($value->cliente_nome) ?></td>
                            <td><?= $chat_started ?></td>
                        </tr>
                    <?php
                    endif;
                endforeach;
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>