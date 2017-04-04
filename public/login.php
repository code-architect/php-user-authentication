<?php require '../includes/init.php'; ?>
<?php include 'parts/header.php'; ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <div class="col-md-6">

                <?php
                //Process the submitted form
                if($_SERVER['REQUEST_METHOD'] == 'POST')
                {
                    if(isset($_POST['login']))
                    {
                        $email = $_POST['email'];
                        $password = $_POST['password'];

                        if(Auth::getInstance()->login($email, $password)){
                            header('Location: admin/index.php');
                        }

                    }
                }
                ?>
                <?php
                if(isset($email)){
                    echo '<div class="alert alert-danger">
                            <strong>Invalid Login!</strong>
                          </div>';
                }
                ?>
                <h1>User Login</h1>
                <form method="post" action="">

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
                    <button type="submit" name="login" class="btn btn-primary">Submit</button>
                </form>
            </div>

        </div>
        <!-- /.row -->

    </div>
    <!-- /.container -->

<?php include 'parts/footer.php'; ?>