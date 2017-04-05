<?php require '../includes/init.php'; ?>
<?php include 'parts/header.php'; ?>
<?php Auth::getInstance()->requireGuest(); ?>
<!-- Page Content -->
<div class="container">

    <div class="row">

        <div class="col-md-6">

            <?php
            //Process the submitted form
            if($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                if(isset($_POST['sign-up']))
                {
                    $users = User::signUp($_POST);
                    if(empty($users->errors))
                    {
                        // redirect to sign up page
                        header('Location: sign-up-success.php');
                    }
                }
            }
            ?>
            <?php
            if(isset($users))
            {
                foreach($users->errors as $error)
                {
                    echo '<div class="alert alert-danger">
                            <strong>Danger!</strong> '.$error.'
                          </div>';
                }
            }
            ?>

            <form method="post" action="register.php">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" id="name"
                           value="<?php echo isset($users)? htmlspecialchars($users->name):''; ?>"
                    >
                </div>
                <div class="form-group">
                    <label for="email">Email address:</label>
                    <input type="email" class="form-control" name="email" id="email"
                           value="<?php echo isset($users)? htmlspecialchars($users->email):''; ?>"
                    >
                </div>
                <div class="form-group">
                    <label for="pwd">Password:</label>
                    <input type="password" class="form-control" name="password" id="pwd">
                </div>
    <!--            <div class="checkbox">-->
    <!--                <label><input type="checkbox"> Remember me</label>-->
    <!--            </div>-->
                <button type="submit" name="sign-up" class="btn btn-primary">Submit</button>
            </form>
        </div>

    </div>
    <!-- /.row -->

    </div>
    <!-- /.container -->

<?php include 'parts/footer.php'; ?>