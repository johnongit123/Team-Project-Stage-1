<?php
declare(strict_types=1);


function get_email(string $email){
        global $con;

        $query = "SELECT email FROM employee WHERE email = ?";

        $stmt = mysqli_prepare($con, $query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
    
        $stmt->bind_result($result);
        $stmt->fetch();
        $stmt->close();
        
        return $result;
}

function get_empid(string $email){
        global $con;

        $query = "SELECT emp_id FROM employee WHERE email = ?";

        $stmt = mysqli_prepare($con, $query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
    
        $stmt->bind_result($result);
        $stmt->fetch();
        $stmt->close();
        
        return $result;
}

function get_password(string $email){
        global $con;
        
        $query = "SELECT password FROM employee WHERE email = ?";

        $stmt = mysqli_prepare($con, $query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        
        $stmt->bind_result($pwd_h_db);
        $stmt->fetch();
        $stmt->close();
        
        return $pwd_h_db;
}


function connect_user(string $email){
        global $con;
        
        $role_query = "SELECT job_role FROM employee WHERE email = ?";
        $role_stmt = $con->prepare($role_query);
            
        $role_stmt->bind_param("s", $email);
        $role_stmt->execute();

        $role_stmt->bind_result($job_role);
        $role_stmt->fetch();
        $role_stmt->close();

         // Redirect the user based on their job role
        if ($job_role !== null) {
                switch ($job_role) {
                case 'admin':
                        header("Location: ../admin/project.php");
                        break;
                case 'team_leader':
                        header("Location: ../teamlead/tasks.php");
                        break;
                case 'employee':
                        header("Location: ../emp/tasks.php");
                        break;
                default:
                        // Default redirect if job role is not recognized
                        header("Location: ../404.html");
                        break;
                }
        } else {
                // Redirect if job role is not found
                header("Location: ../404.html");
        }
        die();
}
    


function collect_login_id(string $email){
        //send to specific site based on job_role in database
        // Query to retrieve the job role of the user
        global $con;
    
        $id_query = "SELECT login_id FROM employee WHERE email = ?";
        $id_stmt = $con->prepare($id_query);
        
        $id_stmt->bind_param("s", $email);
        $id_stmt->execute();
        
        $id_stmt->bind_result($login_Id);
        $id_stmt->fetch();
        $id_stmt->close();

        return $login_Id; 
}
