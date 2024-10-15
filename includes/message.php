<?php 
function renderToast($type, $message) {
    if (!empty($message)): 
        // Assigning classes based on toast type
        $toastClass = '';
        switch ($type) {
            case 'success':
                $toastClass = 'bg-success text-white';
                break;
            case 'error':
                $toastClass = 'bg-danger text-white';
                break;
            case 'warning':
                $toastClass = 'bg-warning text-dark';
                break;
            case 'info':
                $toastClass = 'bg-info text-dark';
                break;
            default:
                $toastClass = 'bg-light text-dark';
        }
?>

<!-- Toast Container -->
<div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3">
    <div id="genericToast" class="toast <?php echo $toastClass; ?>" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto"><?php echo ucfirst($type); ?> Notification</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <?php echo $message; ?>
        </div>
    </div>
</div>

<script>
    // Initialize the toast with a delay of 5 seconds
    var toastEl = document.getElementById('genericToast');
    var toast = new bootstrap.Toast(toastEl, { delay: 5000 });
    toast.show();
</script>

<?php 
    endif; 
}
?>
