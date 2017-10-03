<main class="wrap-main">
	<div class="container-fluid container">
		<div class="row">
			<div class="col-xs-12">
        <legend><i class="fa fa-birthday-cake" aria-hidden="true"></i> Hoy cumplen años</legend>
        <table id="birthdays-table" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
          <thead>
						<tr>
							<th><i class="fa fa-user" aria-hidden="true"></i> Nombre</th>
              <th><i class="fa fa-birthday-cake" aria-hidden="true"></i></th>
							<th><i class="fa fa-calendar" aria-hidden="true"></i></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($this->celebraciones->birthdays() as $users): ?>
            <tr>
              <td><?= $users->first_name ?> <?= $users->last_name ?></td>
              <td>Hoy cumple: <?= Carbon\Carbon::createFromFormat('Y-m-d', $users->fecha_nacimiento)->diffInYears(Carbon\Carbon::now()) ?> años</td>
              <td><?= Carbon\Carbon::createFromFormat('Y-m-d', $users->fecha_nacimiento)->format('d-m-Y') ?></td>
            </tr>
            <?php endforeach ?>
          </tbody>
          <tfoot></tfoot>
        </table>
      </div>
		</div>
	</div>
</main>
