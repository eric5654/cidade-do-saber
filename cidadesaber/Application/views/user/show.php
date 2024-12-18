<?php
use Application\core\ITriggerMessage;
use Application\core\App;
?>
<main>
  <div class="container">
    <div class="row">
      <div class="col-8 offset-2" style="margin-top:100px">

        <?php
        if (!empty($message)) {
          if ($message->getType() == ITriggerMessage::WARNING) {
            ?>
            <div class="alert alert-warning  alert-dismissible" role="alert">
              <?= $message->getMessage() ?>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php
          }
        }
        ?>

        <div class="d-flex justify-content-between mb-3">
          <h2>Visualização do usuário</h2>
          <a href="<?= App::baseUrl("user/index") ?>" class="btn btn-secondary">
            <i class="fas fa-plus"></i> Retornar
          </a>
        </div>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Name</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($data as $user) {
              ?>
              <tr>
                <td><?= $user->id ?></td>
                <td><?= $user->name ?></td>
              </tr>
            <?php
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</main>