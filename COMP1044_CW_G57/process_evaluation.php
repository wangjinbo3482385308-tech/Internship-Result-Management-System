<?php
require_once 'auth_check.php';  //check the user's identity
checkRole('Assessor');
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") { // worked when receiving form data
    $iid = intval($_POST['internship_id']);
    
    // get each score of the items and comments
    $s1 = floatval($_POST['s1']);
    $s2 = floatval($_POST['s2']);
    $s3 = floatval($_POST['s3']);
    $s4 = floatval($_POST['s4']);
    $s5 = floatval($_POST['s5']);
    $s6 = floatval($_POST['s6']);
    $s7 = floatval($_POST['s7']);
    $s8 = floatval($_POST['s8']);
    $comments = $_POST['comments'];

    $scores = [$s1, $s2, $s3, $s4, $s5, $s6, $s7, $s8]; //make sure all the scores are between 0 and 100
    foreach ($scores as $score) {
        if ($score < 0 || $score > 100) {
            $error_msg = "Error: All scores must be between 0 and 100!";
            header("Location: evaluate_student.php?intern_id=$iid&msg=" . urlencode($error_msg) . "&type=error");
            exit();
        }
    }

    //calculate final mark by the percentage of each score
    $final_mark = ($s1 * 0.10) + ($s2 * 0.10) + ($s3 * 0.10) + ($s4 * 0.15) + 
                  ($s5 * 0.10) + ($s6 * 0.15) + ($s7 * 0.15) + ($s8 * 0.15);

    //check if there is the evaluation for this internship in the database
    $check_res = $conn->query("SELECT assessment_id FROM assessments WHERE internship_id = $iid");
    
    if ($check_res->num_rows > 0) {
         // if the record exist, use UPDATE
        $sql = "UPDATE assessments SET 
                task_project_score=?, 
                health_safety_score=?, 
                theoretical_knowledge_score=?, 
                written_report_score=?, 
                language_illustration_score=?, 
                lifelong_learning_score=?, 
                project_mgmt_score=?, 
                time_mgmt_score=?, 
                qualitative_comments=?, 
                final_mark=? 
                WHERE internship_id=?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ddddddddsdi", $s1, $s2, $s3, $s4, $s5, $s6, $s7, $s8, $comments, $final_mark, $iid);
    }
    else {
            //if it is not record, use INSERT
        $sql = "INSERT INTO assessments (
                task_project_score, health_safety_score, theoretical_knowledge_score, 
                written_report_score, language_illustration_score, lifelong_learning_score, 
                project_mgmt_score, time_mgmt_score, qualitative_comments, final_mark, internship_id
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);   //prepare the SQL statement
        $stmt->bind_param("ddddddddsdi", $s1, $s2, $s3, $s4, $s5, $s6, $s7, $s8, $comments, $final_mark, $iid);
    }

    if ($stmt->execute()) { //execute the statement and check if it is working successfully
        $res_msg = "Evaluation saved successfully! Final score: " . $final_mark;
        header("Location: evaluate_student.php?intern_id=$iid&msg=" . urlencode($res_msg) . "&type=success");
    }
    else {
        header("Location: evaluate_student.php?intern_id=$iid&msg=Save failed, please contact admin&type=error");
    }
    exit();
}
?>