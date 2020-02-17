<!DOCTYPE html>
<html>
	<?php
		$page_name = 'Blog';
		include('includes/head.php');
		include('config.php') ;
		$page_limit = 5 ;
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
	?>

	<body class="d-flex vh-100 flex-column justify-content-between">
		<?php include('includes/header.php'); ?>

		<main role="main" class="container">
			<nav aria-label="..." class="mt-4">
				<?php
				$sth = $pdo->prepare('SELECT COUNT(*) FROM message WHERE parent_message = 0');
                $sth->execute();
                $content_count = $sth->fetch()[0];
                ?>
	            <ul class="pagination justify-content-center">
	                <?php
	                $page_count = $content_count / $page_limit;
	                ?>
	                <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
	                    <a class="page-link" href="<?php echo '?page=' . (intval($page) - 1); ?>" tabindex="-1">Précédent</a>
	                </li>
	                <?php
	                for ($i = 1; $i < $page_count + 1; $i++) {
	                    if ($i == $page) {
	                ?>
	                        <li class="page-item active">
	                            <a class="page-link" href="<?php echo '?page=' . $i; ?>"><?php echo $i; ?> <span class="sr-only">(current)</span></a>
	                        </li>
	                    <?php } else { ?>
	                        <li class="page-item">
	                            <a class="page-link" href="<?php echo '?page=' . $i; ?>"><?php echo $i; ?></a>
	                        </li>
	                <?php }
	                } ?>
	                <li class="page-item <?php if ($page >= $page_count) echo 'disabled'; ?>">
	                    <a class="page-link" href="<?php echo '?page=' . (intval($page) + 1); ?>">Suivant</a>
	                </li>
	            </ul>
	        </nav>

			<button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#exampleModalCenter">Publier un nouvel article</button>

			<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Nouvel article</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?php include('../includes/blog/new_post.php'); ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary" form="submit-component">Publier</button>
                    </div>
                </div>
            </div>
        </div>

			<article class="jumbotron d-flex justify-content-center align-items-stretch flex-row">
				<span class="d-block w-75">
					<h1><a href="/post/?id=1">Title of article 1</a></h1>
					<p class="text-justify text-indent">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quaerat voluptatem veritatis iste eligendi illo at quae saepe expedita est consequuntur? Ex, aspernatur. Consequatur maiores nam repellendus praesentium, eligendi optio earum. <a href="#">Continue reading &raquo;</a></p class="text-justify">
				</span>
				<aside class="d-block w-25 ml-5"><img src="/images/logov2.svg" class="rounded d-block w-100" alt="logo du site"></aside>
			</article>

			<article class="jumbotron d-flex justify-content-center align-items-stretch flex-row">
				<span class="d-block w-75">
					<h1><a href="/post/?id=1">Title of article 2</a></h1>
					<p class="text-justify text-indent">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Molestias soluta eaque voluptates! Qui libero ducimus ipsam. Est quis doloribus et amet dolore maxime suscipit saepe ea dicta, inventore hic corporis. <a href="#">Continue reading &raquo;</a></p>
				</span>
				<aside class="d-block w-25 ml-5"><img src="https://via.placeholder.com/150" class="rounded d-block w-100" alt="logo du site"></aside>
			</article>

			<article class="jumbotron d-flex justify-content-center align-items-stretch flex-row">
				<span class="d-block w-75">
					<h1><a href="/post/?id=1">Title of article 3</a></h1>
					<p class="text-justify text-indent">Lorem ipsum dolor sit amet consectetur adipisicing elit. Ea eum minima magni sit ipsa totam obcaecati inventore unde, a libero necessitatibus id iusto alias officia! Autem dolorem hic delectus aspernatur? <a href="#">Continue reading &raquo;</a></p>
				</span>
				<aside class="d-block w-25 ml-5"><img src="https://via.placeholder.com/250x150" class="rounded d-block w-100" alt="logo du site"></aside>
			</article>

			<nav aria-label="..." class="mt-4">
	            <ul class="pagination justify-content-center">
	                <?php
	                $page_count = $content_count / $page_limit;
	                ?>
	                <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
	                    <a class="page-link" href="<?php echo '?page=' . (intval($page) - 1); ?>" tabindex="-1">Précédent</a>
	                </li>
	                <?php
	                for ($i = 1; $i < $page_count + 1; $i++) {
	                    if ($i == $page) {
	                ?>
	                        <li class="page-item active">
	                            <a class="page-link" href="<?php echo '?page=' . $i; ?>"><?php echo $i; ?> <span class="sr-only">(current)</span></a>
	                        </li>
	                    <?php } else { ?>
	                        <li class="page-item">
	                            <a class="page-link" href="<?php echo '?page=' . $i; ?>"><?php echo $i; ?></a>
	                        </li>
	                <?php }
	                } ?>
	                <li class="page-item <?php if ($page >= $page_count) echo 'disabled'; ?>">
	                    <a class="page-link" href="<?php echo '?page=' . (intval($page) + 1); ?>">Suivant</a>
	                </li>
	            </ul>
	        </nav>
		</main>

		<?php include('includes/footer.php'); ?>
	</body>

</html>