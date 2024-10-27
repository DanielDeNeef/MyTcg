<div class="container my-5">
    <h3>Import Collection</h3>
    <form id="bulkUploadForm" enctype="multipart/form-data" method="POST" action="../includes/importCollection.php">
        <input type="hidden" name="userId" value="<?= $_SESSION['id'] ?>">
        
        <div class="mb-3">
            <label for="csvFile" class="form-label">Upload CSV File (SetCode, CardNumber, Amount)</label>
            <input type="file" class="form-control" id="csvFile" name="csvFile" accept=".csv" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Upload Collection</button>
    </form>
</div>
