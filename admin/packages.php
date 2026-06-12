<?php
$page_title = "Packages";
require_once '../config/database.php';
require_once 'header.php';

$msg = "";

// Delete
if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM packages WHERE id = $del_id");
    header("Location: packages.php?msg=deleted");
    exit();
}

// Add new package
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $title    = mysqli_real_escape_string($conn, trim($_POST['title']));
    $duration = mysqli_real_escape_string($conn, trim($_POST['duration']));
    $desc     = mysqli_real_escape_string($conn, trim($_POST['description']));
    $price    = mysqli_real_escape_string($conn, trim($_POST['price']));
    $photo    = mysqli_real_escape_string($conn, trim($_POST['photo_url']));

    $sql = "INSERT INTO packages (title, duration, description, price, photo_url)
            VALUES ('$title', '$duration', '$desc', '$price', '$photo')";
    if (mysqli_query($conn, $sql)) {
        header("Location: packages.php?msg=added");
        exit();
    } else {
        $msg = '<div class="alert alert-danger">Error adding package.</div>';
    }
}

if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'added')   $msg = '<div class="alert alert-success">Package added successfully!</div>';
    if ($_GET['msg'] == 'deleted') $msg = '<div class="alert alert-danger">Package deleted.</div>';
}

$packages = mysqli_query($conn, "SELECT * FROM packages ORDER BY created_at DESC");
?>

<?php echo $msg; ?>

<!-- Add Package Form -->
<div class="card" style="margin-bottom:24px;">
    <div class="card-header">
        <h2>Add New Package</h2>
    </div>
    <div style="padding: 24px 28px;">
        <form method="POST" action="">
            <input type="hidden" name="action" value="add">
            <div class="form-grid">
                <div class="form-group">
                    <label>Package Title</label>
                    <input type="text" name="title" placeholder="e.g. Goa Beach Holiday" required>
                </div>
                <div class="form-group">
                    <label>Duration</label>
                    <input type="text" name="duration" placeholder="e.g. 4 Days / 3 Nights" required>
                </div>
                <div class="form-group">
                    <label>Price</label>
                    <input type="text" name="price" placeholder="e.g. Rs.12,999 per person" required>
                </div>
                <div class="form-group">
                    <label>Photo URL</label>
                    <input type="text" name="photo_url" placeholder="e.g. photo/goa.jpg">
                </div>
                <div class="form-group full">
                    <label>Description</label>
                    <textarea name="description" placeholder="Describe the package..." required></textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-success" style="margin-top:14px;">➕ Add Package</button>
        </form>
    </div>
</div>

<!-- Packages Table -->
<div class="card">
    <div class="card-header">
        <h2>All Packages</h2>
        <span class="badge"><?php echo mysqli_num_rows($packages); ?> Total</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Duration</th>
                <th>Price</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($packages) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($packages)): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['duration']); ?></td>
                    <td><?php echo htmlspecialchars($row['price']); ?></td>
                    <td style="max-width:220px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        <?php echo htmlspecialchars($row['description']); ?>
                    </td>
                    <td>
                        <a href="packages.php?delete=<?php echo $row['id']; ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Delete this package?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align:center; color:#64748b;">No packages found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once 'footer.php'; ?>
