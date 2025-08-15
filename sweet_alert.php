<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- SweetAlert Trigger -->
<script>
    <?php if(isset($msg)) { ?>
        Swal.fire({
            position: 'center', // this puts it in the center of the screen (middle + center)
            icon: '<?php echo $alert_type; ?>', // e.g., 'success', 'error', 'warning'
            title: '<?php echo $msg; ?>',
            showConfirmButton: false,
            timer: 1500
        }).then(() => {
            <?php if($redirect_url) { ?>
                window.location.href = '<?php echo $redirect_url; ?>';
            <?php } ?>
        });
    <?php } ?>
</script>


      

  

