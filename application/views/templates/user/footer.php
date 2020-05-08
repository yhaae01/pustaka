</div>
    <script src="<?= base_url('assets/js/jquery-3.4.1.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/popper.min.js') ?>" ></script>
    <script src="<?= base_url('assets/js/bootstrap.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= base_url('assets/js/jquery.easing.1.3.js'); ?>"></script>
    <!-- Custom scripts for all pages-->
    <script src="<?= base_url('assets/js/sb-admin-2.min.js'); ?>"></script>
    
    <script>
        $('.alert').alert().delay(3000).slideUp('slow');
    </script>

    <script type="text/javascript">
        function confirm_delete() {
            return confirm('Yakin ingin hapus ?');
        }
    </script>

</body>
</html>