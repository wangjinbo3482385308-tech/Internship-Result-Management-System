<?php
require_once 'auth_check.php';  //check the identity of the user
checkRole('Assessor'); 
require_once 'db.php';   //build the connection with database

if (!isset($_GET['intern_id'])) {
    echo "Internship info not found";   //check if the internship id is available
    exit();
}
$internshipID = intval($_GET['intern_id']);
$user_id = $_SESSION['user_id'];    //get the user id from session

$sql = "SELECT s.student_name, s.student_id, i.company_name
        FROM internships i 
        JOIN students s ON i.student_id = s.student_id 
        WHERE i.internship_id = ? AND i.assessor_id = ?";   //get student and company information
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $internshipID, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {    //print error if the student information is not found
    die("No such record in the database");
}
// check weather the record exist in the database
$check_sql = "SELECT * FROM assessments WHERE internship_id = $internshipID";
$existing_query = $conn->query($check_sql);
$existing = $existing_query->fetch_assoc();

$msg = isset($_GET['msg']) ? $_GET['msg'] : ""; //get the message from URL
$type = isset($_GET['type']) ? $_GET['type'] : "";
?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Grading System - Evaluation Form</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    <div class="container">
        <h2>Intern Evaluation Form</h2>
        <div style="margin-bottom: 10px;">
            <a href="assessor_dashboard.php">Return to Dashboard</a>
        </div>

        <div class="info-box">
            <p><strong>Student Name:</strong> <?php echo $student['student_name']; ?> (ID: <?php echo $student['student_id']; ?>)</p>
            <p><strong>Internship Company:</strong> <?php echo $student['company_name']; ?></p>
        </div>

        <?php if($msg != ""): ?>
            <div class="<?php echo $type; ?>-msg" style="color: red; margin: 10px 0;">
                <?php echo htmlspecialchars($msg); ?>
            </div>
        <?php endif; ?>

        <form action="process_evaluation.php" method="post">
            <input type="hidden" name="internship_id" value="<?php echo $internshipID; ?>">
            
            <div class="score-grid">
                <p>Please score the following items (0-100):</p>
                <label>Undertaking Tasks/Projects – 10%</label>
                <input type="number" name="s1" step="0.01" min="0" max="100" value="<?php echo isset($existing['task_project_score']) ? $existing['task_project_score'] : ''; ?>" required>
                
                <label>Health and Safety Requirements at the Workplace – 10%</label>
                <input type="number" name="s2" step="0.01" min="0" max="100" value="<?php echo isset($existing['health_safety_score']) ? $existing['health_safety_score'] : ''; ?>" required>

                <label>Connectivity and Use of Theoretical Knowledge – 10%</label>
                <input type="number" name="s3" step="0.01" min="0" max="100" value="<?php echo isset($existing['theoretical_knowledge_score']) ? $existing['theoretical_knowledge_score'] : ''; ?>" required>

                <label>Presentation of the Report as a Written Document – 15%</label>
                <input type="number" name="s4" step="0.01" min="0" max="100" value="<?php echo isset($existing['written_report_score']) ? $existing['written_report_score'] : ''; ?>" required>

                <label>Clarity of Language and Illustration – 10%</label>
                <input type="number" name="s5" step="0.01" min="0" max="100" value="<?php echo isset($existing['language_illustration_score']) ? $existing['language_illustration_score'] : ''; ?>" required>

                <label>Lifelong Learning Activities – 15%</label>
                <input type="number" name="s6" step="0.01" min="0" max="100" value="<?php echo isset($existing['lifelong_learning_score']) ? $existing['lifelong_learning_score'] : ''; ?>" required>

                <label>7. Project Management – 15%</label>
                <input type="number" name="s7" step="0.01" min="0" max="100" value="<?php echo isset($existing['project_mgmt_score']) ? $existing['project_mgmt_score'] : ''; ?>" required>

                <label>Time Management – 15% </label>
                <input type="number" name="s8" step="0.01" min="0" max="100" value="<?php echo isset($existing['time_mgmt_score']) ? $existing['time_mgmt_score'] : ''; ?>" required>
            </div>

            <div style="margin-top: 15px;">
                <label>total internship score:</label><br>
                <textarea name="comments" rows="5" style="width: 100%;" required><?php echo isset($existing['qualitative_comments']) ? $existing['qualitative_comments'] : ''; ?></textarea>
            </div>

            <button type="submit" style="margin-top: 10px; padding: 5px 20px;">Submit Evaluation</button>
        </form>

        <?php if(isset($existing['final_mark'])): ?>       
            <div class="result-box" style="margin-top: 20px; border-top: 1px dashed #ccc;">
                <p>total internship score in system: <span style="font-size: 20px; color: blue;"><?php echo $existing['final_mark']; ?></span></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
