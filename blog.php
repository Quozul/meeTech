<!DOCTYPE html>
<html>
<?php
$head = "Blog";
include('includes/head.php');
?>

<body>
	<?php include('includes/header.php'); ?>

	<main role="main" class="container">
		<article class="jumbotron d-flex justify-content-center align-items-stretch flex-row">
			<span class="d-block w-75">
				<h1><a href="/message/?id=1">Title of article 1</a></h1>
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quaerat voluptatem veritatis iste eligendi illo at quae saepe expedita est consequuntur? Ex, aspernatur. Consequatur maiores nam repellendus praesentium, eligendi optio earum. <a href="#">Continue reading &raquo;</a></p>
			</span>
			<aside class="d-block w-25"><img src="/images/logov2.svg" class="rounded d-block w-100" alt="logo du site"></aside>
		</article>

		<article class="jumbotron d-flex justify-content-center align-items-stretch flex-row">
			<span class="d-block w-75">
				<h1><a href="/message/?id=1">Title of article 2</a></h1>
				<p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Molestias soluta eaque voluptates! Qui libero ducimus ipsam. Est quis doloribus et amet dolore maxime suscipit saepe ea dicta, inventore hic corporis. <a href="#">Continue reading &raquo;</a></p>
			</span>
			<aside class="d-block w-25"><img src="https://via.placeholder.com/150" class="rounded d-block w-100" alt="logo du site"></aside>
		</article>

		<article class="jumbotron d-flex justify-content-center align-items-stretch flex-row">
			<span class="d-block w-75">
				<h1><a href="/message/?id=1">Title of article 2</a></h1>
				<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ea eum minima magni sit ipsa totam obcaecati inventore unde, a libero necessitatibus id iusto alias officia! Autem dolorem hic delectus aspernatur? <a href="#">Continue reading &raquo;</a></p>
			</span>
			<aside class="d-block w-25"><img src="https://via.placeholder.com/250x150" class="rounded d-block w-100" alt="logo du site"></aside>
		</article>
	</main>

	<?php include('includes/footer.php'); ?>
</body>

</html>