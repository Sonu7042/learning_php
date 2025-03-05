<?php
include 'config.php';           //I have push code on github. this code is push by sonu

$val = 1;
//signup
if (isset($_POST['signup'])) {
    $val = 0;
    $fullname = $_POST['fullname'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm-password'] ?? '';

    $errorCount = 0;
    $returnData['success'] = false;

    if ($fullname == '') {
        $returnData['nameError'] = 'Name is required';
        $errorCount++;
    }

    if ($email == '') {
        $returnData['emailError'] = 'Email is required';
        $errorCount++;
    }

    if ($password == '') {
        $returnData['passwordError'] = 'Password is required';
        $errorCount++;
    }

    if ($confirmPassword == '') {
        $returnData['confirmPasswordError'] = 'Confirm password is required';
        $errorCount++;
    } elseif ($password !== $confirmPassword) {
        $returnData['passwordMismatchError'] = 'Passwords do not match';
        $errorCount++;
    }

    if ($errorCount == 0) {
        $checkEmail = "SELECT id FROM users WHERE email = '$email'";
        $emailResult = mysqli_query($con, $checkEmail);

        // echo $emailResult;
        if (mysqli_num_rows($emailResult) > 0) {
            $returnData['emailError'] = 'email already exist';
        } else {
            $sql = "INSERT INTO users (name, email, password) VALUES ('$fullname', '$email', '$password')";
            $result = mysqli_query($con, $sql);

            if ($result) {
                $id = $con->insert_id;
                $sql = "select id, name, email from users where id = '$id'";
                $run = mysqli_query($con, $sql);
                $row = mysqli_fetch_assoc($run);

                $_SESSION['loginId'] = $id;
                $_SESSION['loginUserData'] = $row;
                $returnData['success'] = true;
                $returnData['message'] = 'registered successfully';
            } else {
                $returnData['message'] = 'something went wrong';
            }
        }
    }
    echo json_encode($returnData, true);
}


//Login
if (isset($_POST['login'])) {
    $val = 0;
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $returnData['success'] = false;
    $errorCount = 0;

    if ($email == '') {
        $returnData['emailError'] = 'email is required';
        $errorCount++;
    }

    if ($password == '') {
        $returnData['password'] = 'password is required';
        $errorCount++;
    }

    if ($errorCount == 0) {
        $checkEmail = "SELECT id, name, email, password FROM users WHERE email = '$email'";
        $emailResult = mysqli_query($con, $checkEmail);


        if (mysqli_num_rows($emailResult) > 0) {

            $user = mysqli_fetch_assoc($emailResult);
            $dbPassword = $user['password'];


            if ($dbPassword === $password) {
                $_SESSION['loginId'] = $user['id'];
                $_SESSION['loginUserData'] = $user;
                $returnData['success'] = true;
                $returnData['message'] = 'login successfully';
            } else {
                $returnData['passwardError'] = 'password is incorrect';
            }
        } else {
            $returnData['notFound'] = 'User not found';
        }
    }

    echo json_encode($returnData, true);
}

//todo apis
// add task apis
if (isset($_POST['addTask'])) {
    $val = 0;

    $task = $_POST['task'] ?? '';
    $userId = $_POST['userId'] ?? '';

    $returnData = ['success' => false];
    $errorCount = 0;

    if ($task == "") {
        $returnData['message'] = "Please enter a task";
        $errorCount++;
    }

    if ($errorCount == 0) {
        $sql = "INSERT into todo (task, create_at, update_at) values('$task', '$userId', '$userId')";
        $result = mysqli_query($con, $sql);
        if ($result) {
            $sql = "SELECT * FROM todo where create_at='$userId' order by id DESC";
            $result = mysqli_query($con, $sql);

            if ($result) {
                $data = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $data[] = $row;
                }
                // print_r($data); die;
                $returnData['taskList'] = $data;
                $returnData['success'] = true;
                $returnData['message'] = "task added successfully";
            }
        } else {
            $returnData['message'] = "something went wrong";
        }
    }
    echo json_encode($returnData, true);
}


//read data api
if (isset($_POST['todoList'])) {
    $val = 0;
    $returnData['success'] = false;
    $userId = $_POST['userId'] ?? '';

    if ($userId) {
        $returnData['error'] = "UserId is required";
    }

    $sql = "SELECT * FROM todo where create_at = '$userId' order by id DESC ";
    $result = mysqli_query($con, $sql);

    if ($result) {
        $data = [];
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        }
        $returnData['success'] = true;
        $returnData['tasks'] = $data;
    } else {
        $returnData['error'] = 'something went wrong';
    }
    echo json_encode($returnData, true);
}


//delete data api
if (isset($_POST['delete'])) {
    $val = 0;
    $id = $_POST['id'];
    // echo $id;die;
    $returnData['success'] = false;
    $query = "delete from todo where id= '$id'";
    $result = mysqli_query($con, $query);
    if ($result) {
        $returnData['success'] = true;
        $returnData['msg'] = "data is deleted";
    }

    echo json_encode($returnData, true);
}



//update data api
if (isset($_POST['update'])) {
    $val = 0;
    $id = $_POST['id'];
    $task = $_POST['task'];

    $returnData['success'] = false;

    $query = "update todo set task='$task' where id='$id'";
    $result = mysqli_query($con, $query);

    if ($result) {
        $returnData['success'] = true;
        $returnData['msg'] = "data is updated";
    } else {
        $returnData['error'] = "something went wrong";
    }


    echo json_encode($returnData, true);
}



// add new List api
if (isset($_POST['addList'])) {
    $val = 0;
    $listName = $_POST['listName'] ?? "";
    $temp_list = $_POST['temp_list'] ?? "";
    $list_no = $_POST['list_no'] ?? "";
    
    $returnData['success'] = false;
    $errorCount = 0;
    if ($listName == "") {
        $returnData['error'] = "list name is required";
        $errorCount++;
    }
    if ($errorCount == 0) {
        $sql = "insert into newlists (list, list_no, temp_list) values ('$listName', '$list_no', '$temp_list')";
        $result = mysqli_query($con, $sql);
        if ($result) {
            $lastId = $con->insert_id;
            $sql = "SELECT id, list, temp_list, list_no FROM newlists where id = '$lastId'";
            $result = mysqli_query($con, $sql);
            if ($row= mysqli_fetch_assoc($result)) {
                $data[]=$row;
                $returnData['success'] = true;
                $returnData['allLists'] = $data;
            } else {
                $returnData['message'] = "Data not found";
            }
        }
    }
    echo json_encode($returnData, true);
}


//fetch all lists api
if (isset($_POST['fetchAllLists'])) {
    $val = 0;
    $returnData['success'] = false;

    $sql = "SELECT * FROM newlists order by id ASC";
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) > 0) {
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        $returnData['success'] = true;
        $returnData['data'] = $data;
    } else {
        $returnData['message'] = "Data not found";
    }
    echo json_encode($returnData, true);
}


// delete list
if (isset($_POST['deleteList'])) {
    $id = $_POST["id"];
    $val = 0;
    $returnData['success'] = false;

    if ($id === "") {
        $returnData['error'] = "Id is required";
    } else {
        $sql = "DELETE FROM newlists WHERE id='$id'";
        $result = mysqli_query($con, $sql);

        if ($result) {
            $returnData['success'] = true;
            $returnData['message'] = "List Deleted Successfully";
        }
    }
    echo json_encode($returnData, true);
}


if (isset($_POST['updateList'])) {
    $val = 0;
    $id = $_POST["id"] ?? "";
    $name = $_POST["listName"] ?? "";
    $returnData['success'] = false;
    $errorCount = 0;

    if ($id == "") {
        $returnData['errorid'] = "Id is required";
        $errorCount++;
    }

    if ($name == "") {
        $returnData['errorName'] = "List Name is required";
        $errorCount++;
    }

    if ($errorCount == 0) {
        $sql = "UPDATE newlists set list='$name' where id='$id'";
        $result = mysqli_query($con, $sql);
        if ($result) {
            $returnData['success'] = true;
            $returnData['message'] = "List Updated Successfully";
        }
    }
    echo json_encode($returnData, true);
}


if ($val) {
    $returnData['success'] = false;
    $returnData['error'] = "Something went wrong";

    echo json_encode($returnData, true);
}
