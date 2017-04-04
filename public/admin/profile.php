<?php require '../../includes/init.php'; ?>
<?php require '../parts/admin_header.php'; ?>


    <div class="container">

        <div class="row">
            <?php $user = Auth::getInstance()->getCurrentUser(); ?>

            <h2>Hello User</h2>
            <p>You are registered here as : <strong><?php echo htmlspecialchars($user->name); ?></strong></p>
            <p>Your email id is : <strong><?php echo htmlspecialchars($user->email); ?></strong></p>

        </div>
        <!-- /.row -->
    </div>
    <!-- /.container -->

<?php include '../parts/footer.php'; ?>