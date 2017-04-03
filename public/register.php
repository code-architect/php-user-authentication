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
                if(isset($_POST['sign-up']))
                {
                    User::signUp($_POST);

                    // redirect to sign up page
                    header('Location: sign-up-success.php');
                }
            }

            ?>


            <form method="post" action="register.php">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" id="name">
                </div>
                <div class="form-group">
                    <label for="email">Email address:</label>
                    <input type="email" class="form-control" name="email" id="email">
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