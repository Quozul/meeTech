<!DOCTYPE html>
<html>
    <?php
		$page_name = 'About';
		include('includes/head.php');
	?>

    <body class="d-flex vh-100 flex-column justify-content-between">

        <?php include('includes/header.php'); ?>

        <main role="main" class="container">
            <div class="jumbotron">
                <h1>Description</h1>
                <p class="lead">meeTech is a technology website where you can share and learn new tech-related information.</p>
                <p class="text-justify">You can configure a fictive computer and see its theoretical performances, compare it to other builds and share it to the community. If your knowledge of computer is limited, the website also has functionality with which you can specify the usage that your next computer will be for and we’ll calculate the perfect build. Also, if you don’t know how to build a computer, you can simply ask other users for help!</p>
                <p>See the <a href="/brand/">branding here</a>.</p>
            </div>

            <div class="jumbotron">
                <h1>Tools used</h1>
                <p class="lead">Here is a list of all softwares used to develop this website.</p>
                <ul>
                    <li><b>Code syncing:</b> GitHub</li>
                    <li><b>Task organization:</b> Trello</li>
                    <li><b>Logo:</b> Adobe Illustrator</li>
                    <li><b>Design:</b> Figma</li>
                    <li><b>Development:</b> Visual Studio Code, Atom, Sublime Text</li>
                    <li><b>Hosting:</b> Apache2, PHP, MAMP <i class="text-muted">(local testings)</i></li>
                    <li><b>Database:</b> MariaDB</li>
                </ul>
            </div>
        </main>

        <?php include('includes/footer.php'); ?>

    </body>

</html>