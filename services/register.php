<?php
    session_start();
    
    include_once "requestAPI.php";

    if (isset($_POST['login'])){
        $user = isset($_POST['user']) ? $_POST['user'] : null;
        $pwd = isset($_POST['password']) ? $_POST['password'] : null;

        $postData = array (
            "userId" => $user,
            "password" => $pwd
        );

        $response = requestApi('POST', 'http:/localhost:8080/login', $postData);

        if (strcmp($response, "OK")){
            // session_cache_limiter('private');
            // session_cache_expire(60);
            
            $_SESSION['token'] = $response;
            $_SESSION['name'] = json_decode(requestApi('GET', 'http:/localhost:8080/dentists/' . $user, false, $_SESSION['token']))->name;
            echo "<script> window.location = '../index.php' </script>";
        } else {
            echo "<script> window.location = '../login.php?response=unauthorized' </script>";
        }

    } else {
        if (isset($_POST['registerProcedure'])){
            $procedureTitle = isset($_POST['title']) ? $_POST['title'] : null;
            $procedureDescription = isset($_POST['description']) ? $_POST['description'] : null;
    
            $postData = array (
                "title" => $procedureTitle,
                "description" => $procedureDescription
            );
    
            $response = requestApi('POST', 'http:/localhost:8080/procedures/', $postData, $_SESSION['token']);

            echo "<script> window.location = '../procedure-list.php?response=" . $response . "' </script>";
        } else {
            if (isset($_POST['updateProcedure'])){
                $procedureId = isset($_POST['id']) ? $_POST['id'] : null;
                $procedureTitle = isset($_POST['title']) ? $_POST['title'] : null;
                $procedureDescription = isset($_POST['description']) ? $_POST['description'] : null;
        
                $postData = array (
                    "id" => $procedureId,
                    "title" => $procedureTitle,
                    "description" => $procedureDescription
                );
        
                $response = requestApi('POST', 'http:/localhost:8080/procedures/', $postData, $_SESSION['token']);
    
                echo "<script> window.location = '../procedure-list.php?response=" . $response . "' </script>";
            }
        }
    }
?>