<?php
require_once 'auth_check.php';
require_once 'db.php';
checkRole('Admin'); 

$search = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT s.student_id, s.student_name, s.programme, i.internship_id, i.company_name, a.final_mark 
        FROM students s
        LEFT JOIN internships i ON s.student_id = i.student_id
        LEFT JOIN assessments a ON i.internship_id = a.internship_id";

if (!empty($search)) {
    $search = $conn->real_escape_string($search); 
    $sql .= " WHERE s.student_id LIKE '%$search%' OR s.student_name LIKE '%$search%'";
}

$sql .= " ORDER BY s.student_id ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Results - Internship Management System</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h2>All Internship Results</h2>
            <a href="admin_dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
        </header>

        <form method="GET" action="" class="action-bar" style="align-items: center;">
            <input type="text" name="search" placeholder="Search by name or ID..." value="<?php echo htmlspecialchars($search); ?>" style="flex: 1; padding: 8px;">
            <button type="submit" class="btn btn-primary">Search</button>
            <?php if(!empty($search)): ?>
                <a href="admin_results.php" class="btn btn-secondary">Clear</a>
            <?php endif; ?>
        </form>

        <table class="data-table">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Major</th>
                    <th>Company</th>
                    <th>Final Mark</th>
                    <th>Action</th> 
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo htmlspecialchars($row['student_id']); ?></td>
        <td><?php echo htmlspecialchars($row['student_name']); ?></td>
        <td><?php echo htmlspecialchars($row['programme']); ?></td>
        <td><?php echo htmlspecialchars($row['company_name']) ?: '<span style="color:gray;">Not Assigned</span>'; ?></td>
        <td>
            <strong><?php echo ($row['final_mark'] !== null) ? $row['final_mark'] : 'Not Evaluated'; ?></strong>
        </td>
        <td>
            <?php if(!empty($row['company_name'])): ?>
                <a href="delete_internship.php?id=<?php echo $row['internship_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to cancel this student's internship assignment?')">Unassign</a>
            <?php else: ?>
                <span style="color:gray;">N/A</span>
            <?php endif; ?>
        </td>
    </tr>
<?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5">No records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
