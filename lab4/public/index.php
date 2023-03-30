<?php
require_once "../bootstrap.php";
use CT275\Labs\Contact;
$contact = new Contact($PDO);
$contacts = $contact->all();
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Contacts</title>

	<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
	<link href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" rel="stylesheet">
	<link href="//cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" rel="stylesheet">
	<link href="<?= BASE_URL_PATH . "css/sticky-footer.css" ?>" rel=" stylesheet">
	<link href="<?= BASE_URL_PATH . "css/font-awesome.min.css" ?>" rel=" stylesheet">
	<link href="<?= BASE_URL_PATH . "css/animate.css" ?>" rel=" stylesheet">
</head>

<body>
	<?php include('../partials/navbar.php') ?>

	<!-- Main Page Content -->
	<div class="container">
		<section id="inner" class="inner-section section">
			<!-- SECTION HEADING -->
			<h2 class="section-heading text-center wow fadeIn" data-wow-duration="1s">Contacts</h2>
			<div class="row">
				<div class="col-md-6 col-md-offset-3 text-center">
					<p class="wow fadeIn" data-wow-duration="2s">View your all contacs here.</p>
				</div>
			</div>

			<div class="inner-wrapper row">
				<div class="col-md-12">

					<a href="<?= BASE_URL_PATH . 'add.php' ?>" class="btn btn-primary" style="margin-bottom: 30px;">
						<i class="fa fa-plus"></i> New Contact</a>

					<!-- Table Starts Here -->
					<table id="contacts" class="table table-bordered table-responsive table-striped">
						<thead>
							<tr>
								<th>Name</th>
								<th>Phone</th>
								<th>Date Created</th>
								<th>Notes</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($contacts as $contact): ?>
								<tr>
									<td><?=htmlspecialchars($contact->name)?></td>
									<td><?=htmlspecialchars($contact->phone)?></td>
									<td><?=date("d-m-Y", strtotime($contact->created_at))?></td>
									<td><?=htmlspecialchars($contact->notes)?></td>
									<td>
										<a href="<?=BASE_URL_PATH . 'edit.php?id=' . $contact->getId()?>"
											class="btn btn-xs btn-warning">
											<i alt="Edit" class="fa fa-pencil"> Edit</i></a>
										<form class="delete" action="<?=BASE_URL_PATH . 'delete.php'?>"
												method="POST" style="display: inline;">
											<input type="hidden" name="id"
												value="<?=$contact->getId()?>">
											<button type="submit" class="btn btn-xs btn-danger"
												name="delete-contact">
												<i alt="Delete" class="fa fa-trash"> Delete</i></a></button>
										</form>
									</td>
								</tr>
							<?php endforeach ?>
						</tbody>
					</table>
					<!-- Table Ends Here -->
				</div>
			</div>
		</section>
	</div>
	<div id="delete-confirm" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close"
						data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Confirmation</h4>
				</div>
				<div class="modal-body">DO you want to delete this contact?</div>
				<div class="modal-footer">
					<button type="button" data-dismiss="modal"
						class="btn btn-danger" id="delete">Delete</button>
					<button type="button" data-dismiss="modal"
						class="btn btn-default">Cancel</button>
				</div>
			</div>
		</div>
	</div>
	
	<?php include('../partials/footer.php') ?>

	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
	<script src="//cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
	<script src="<?= BASE_URL_PATH . "js/wow.min.js" ?>"></script>
	<script>
		$(document).ready(function() {
			new WOW().init();
			$('#contacts').DataTable();

			$('button[name="delete-contact"]').on('click', function(e){
				e.preventDefault();

				const form = $(this).closest('form');
				const nameTd = $(this).closest('tr').find('td:first');
				if (nameTd.length > 0) {
					$('.modal-body').html(
						`Do you want to delete "${nameTd.text()}"`
					);
				}
				$('#delete-confirm').modal({
					backdrop: 'static', keyboard: false
				})
				.one('click', '#delete', function() {
					form.trigger('submit');
				});
			});
		});
	</script>
</body>

</html>