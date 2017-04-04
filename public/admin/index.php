<?php require '../../includes/init.php'; ?>
<?php require '../parts/admin_header.php'; ?>


<div class="container">

    <div class="row">

        <h1>Welcome <?php echo htmlspecialchars(Auth::getInstance()->getCurrentUser()->name);  ?></h1>



    </div>
    <!-- /.row -->
</div>
    <!-- /.container -->

<?php include '../parts/footer.php'; ?>