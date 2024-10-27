<?php
include 'dbconnect.php';

// Check if userId is provided and valid
if (!isset($_POST['userId']) || empty($_POST['userId'])) {
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'User ID not provided.'];
    header('Location: ../pages/collection.php');
    exit();
}

$userId = (int)$_POST['userId'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if file was uploaded
    if (isset($_FILES['csvFile']) && $_FILES['csvFile']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['csvFile']['tmp_name'];
        $fileName = $_FILES['csvFile']['name'];
        $fileType = $_FILES['csvFile']['type'];
        
        // Check if the file is a CSV
        if ($fileType === 'text/csv' || strtolower(pathinfo($fileName, PATHINFO_EXTENSION)) === 'csv') {
            $handle = fopen($fileTmpPath, 'r');
            if ($handle !== FALSE) {
                
                $conn->begin_transaction();

                try {
                    //Clear existing collection for the user
                    $deleteQuery = "DELETE FROM Collection WHERE UserId = ?";
                    $deleteStmt = $conn->prepare($deleteQuery);
                    $deleteStmt->bind_param('i', $userId);
                    $deleteStmt->execute();

                    //Insert new data from CSV
                    $rowCount = 0;
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

                        //skip the header row
                        if ($rowCount === 0) {
                            $rowCount++;
                            continue;
                        }

                        // Extract data from the row (SetCode, CardNumber, Amount)
                        $setCode = $data[0];
                        $cardNumber = $data[1];
                        $amount = (int)$data[2];

                        // get the CardId using SetCode and CardNumber
                        $cardQuery = "
                            SELECT c.id 
                            FROM Card c 
                            JOIN cardSet cs ON c.gameSetId = cs.id
                            WHERE cs.Code = ? AND c.CardNumber = ?
                        ";
                        $cardStmt = $conn->prepare($cardQuery);
                        $cardStmt->bind_param('si', $setCode, $cardNumber);
                        $cardStmt->execute();
                        $cardResult = $cardStmt->get_result();

                        if ($cardResult->num_rows > 0) {
                            // get the CardId and insert into Collection
                            $cardRow = $cardResult->fetch_assoc();
                            $cardId = $cardRow['id'];

                            // Insert new collection data
                            $insertQuery = "INSERT INTO Collection (UserId, CardId, Amount) VALUES (?, ?, ?)";
                            $insertStmt = $conn->prepare($insertQuery);
                            $insertStmt->bind_param('iii', $userId, $cardId, $amount);
                            $insertStmt->execute();
                        }

                        $rowCount++;
                    }
                    fclose($handle);
                    
                    // Commit transaction
                    $conn->commit();
                    
                    // Set success message and redirect
                    $_SESSION['toast'] = ['type' => 'success', 'message' => 'Collection imported successfully.'];
                    header('Location: ../pages/collection.php');
                } catch (Exception $e) {
                    $conn->rollback();
                    $_SESSION['toast'] = ['type' => 'error', 'message' => 'Error importing collection: ' . $e->getMessage()];
                    header('Location: ../pages/collection.php');
                }
            } else {
                $_SESSION['toast'] = ['type' => 'error', 'message' => 'Error opening the CSV file.'];
                header('Location: ../pages/collection.php');
            }
        } else {
            $_SESSION['toast'] = ['type' => 'error', 'message' => 'Only CSV files are allowed.'];
            header('Location: ../pages/collection.php');
        }
    } else {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'File upload failed.'];
        header('Location: ../pages/collection.php');
    }
}
?>
