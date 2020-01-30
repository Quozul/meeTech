<!DOCTYPE html>
<html>
	<?php
		$page_name = 'Forum';
		include('includes/head.php');
	?>

	<body class="d-flex vh-100 flex-column justify-content-between">
		<?php include('includes/header.php'); ?>

		<main role="main" class="container">
			<section class="jumbotron">
				<h1>Forum</h1>
				<a href="#" class="text-decoration-none" title="Lorem ipsum dolor sit amet consectetur adipisicing elit.">
					<article class="border border-light rounded p-2 mb-1">
						<h5 class="text-truncate">Lorem ipsum dolor sit amet consectetur adipisicing elit.</h5>
						<span class="text-muted">By Author • 30 January 2020</span>
					</article>
				</a>
				<a href="#" class="text-decoration-none" title="Reiciendis nulla voluptate libero magnam rem neque dicta quia officia.">
					<article class="border border-light rounded p-2 mb-1">
						<h5 class="text-truncate">Reiciendis nulla voluptate libero magnam rem neque dicta quia officia.</h5>
						<span class="text-muted">By Author • 30 January 2020</span>
					</article>
				</a>
				<a href="#" class="text-decoration-none">
					<article class="border border-light rounded p-2 mb-1" title="Aspernatur quaerat eveniet, vitae iusto maxime saepe sapiente rem dolore fugit eligendi sint voluptatibus odio nemo?">
						<h5 class="text-truncate">Aspernatur quaerat eveniet, vitae iusto maxime saepe sapiente rem dolore fugit eligendi sint voluptatibus odio nemo?</h5>
						<span class="text-muted">By Author • 30 January 2020</span>
					</article>
				</a>
			</section>
		</main>

		<?php include('includes/footer.php'); ?>
	</body>

</html>