<?php
$url = getRoot() . '/installer/?step=4';
$action = getRoot();

if(!isset($_SESSION['username']) && !isset($_SESSION['password'])) {
	header('Location: ' . $url);
}
unlink(INSTALL_FILE);
?>

<div class="starter-template">
	<h1>Installation Completed</h1>
	<p>You have successfully installed OpenEMIS School. Please click Start to launch OpenEMIS School.</p>


    <div class="row">
        <div class="col-md-12">
            <form class="form-horizontal formCreateDbUser" method="post" action="<?php echo $action; ?>">
                <input type="hidden" class="form-control" name="username" value="<?php echo $_SESSION['username']; ?>" />
                <input type="hidden" class="form-control" name="password" value="<?php echo $_SESSION['password']; ?>" />
                <div class="form-group">
                    <div class="col-sm-offset-5 col-sm-10">
                        <input type="submit" class="btn btn-success" name="createUser" value="Start" style="margin-left: 35px;" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>