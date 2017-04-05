<?php require '../includes/init.php'; ?>
<?php include 'parts/header.php'; ?>
<?php Auth::getInstance()->requireGuest(); ?>
    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <div class="col-md-6">

                <?php
                // check the status of the remember me box
                $remember_me = isset($_POST['remember_me']);

                //Process the submitted form
                if($_SERVER['REQUEST_METHOD'] == 'POST')
                {
                    if(isset($_POST['login']))
                    {
                        $email = $_POST['email'];
                        $password = $_POST['password'];

                        if(Auth::getInstance()->login($email, $password, $remember_me)){

                            // redirect to intended page or home page
                            if(isset($_SESSION['return_to'])){
                                $url = $_SESSION['return_to'];
                                unset($_SESSION['return_to']);
                                redirect($url);
                            }else{
                                header('Location: admin/index.php');
                            }
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
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember_me" value="1"
                                            <?php if($remember_me): ?>checked="checked" <?php endif; ?>>
                                        Remember me
                                    </label>
                                </div>
                    <button type="submit" name="login" class="btn btn-primary">Submit</button>
                </form>
            </div>

        </div>
        <!-- /.row -->

    </div>
    <!-- /.container -->

<?php include 'parts/footer.php'; ?>