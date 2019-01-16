<?php
    require_once 'project.php';
    class DB{
        public static function connect(){
            $conn = new mysqli("localhost","root","","portfolio") or die("Error connectiong to the database: " . $conn->error);
            return $conn;
        }
        public static function getAll($limit = 9,$tag = "notset"){
            $conn = DB::connect();
            if($tag == "notset")
                $res = $conn->query("SELECT * FROM projects LIMIT $limit");
            else   
                $res = $conn->query("SELECT * FROM projects WHERE tags LIKE '%$tag%' LIMIT $limit") or die($conn->error);
            $ret = array();
            
            while($row = $res->fetch_assoc())
                array_push($ret,new Project($row['id'],$row['name'],$row['description'],$row['image'],$row['date'],$row['tags'],DB::getViews($row['id'])));
            return $ret;
        }
        public static function get($id){
            $conn = DB::connect();
            $IP = $_SERVER['REMOTE_ADDR'];
            $res = $conn->query("SELECT * FROM views WHERE projectID = $id AND IP = '$IP'") or die($conn->error);
            if($res->num_rows == 0)
                $conn->query("INSERT INTO views VALUES ($id,'$IP')") or die($conn->error);
            $res = $conn->query("SELECT * FROM projects WHERE id = $id") or die($conn->error);
            while($row = $res->fetch_assoc())
                $ret = new Project($row['id'],$row['name'],$row['description'],$row['image'],$row['date'],$row['tags'],DB::getViews($row['id']));
            return $ret;
        }
        public static function getViews($id){
            $viewsRes = DB::connect()->query("SELECT * FROM views WHERE projectID = $id");
            return $viewsRes->num_rows;
        }
        public static function getConfig(){
            $conn = DB::connect();
            $res = $conn->query("SELECT name,value FROM config") or die($conn->error);
            $config = array();
            while($row = $res->fetch_assoc())
                $config[$row['name']] = $row['value'];
            return $config;
        }
        public static function handleUpdate(){
            $conn = DB::connect();
            $stmt = $conn->prepare("UPDATE config SET value = ? WHERE name = ?") or die(mysqli_error($conn)); 
            $stmt->bind_param("ss",$value,$key);
            $key = "username";
            $value = $_POST["username"];
            $stmt->execute() or die("error : <br>" . $conn->error );
            $key = "description";
            $value = $_POST["description"];
            $stmt->execute()  or die("error : <br>" . $conn->error );
            $key = "email";
            $value = $_POST["email"];
            $stmt->execute()  or die("error : <br>" . $conn->error );
            $key = "about";
            $value = $_POST["about"];
            $stmt->execute()  or die("error : <br>" . $conn->error );
            $key = "image";
            $value = DB::handleImageUpload($_FILES["image"]);
            $stmt->execute()  or die("error : <br>" . $conn->error );
            $stmt->close();
            header("Location: ../admin/profile.php");
        }
        public static function handleImageUpload($fileToUpload,$delete=true){
            $target_dir = "../uploads/";
            $target_file = $target_dir . basename($fileToUpload["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if file already exists
            $i = 1;
            while(file_exists($target_file)) {
                $path_parts = pathinfo($target_file);
                $target_file = $path_parts['dirname'] . "/" . $path_parts['filename'] . "_" . $i ."." . $path_parts['extension'];
                $i++;
            }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) 
                return "error1";
            else
                if (move_uploaded_file($fileToUpload["tmp_name"], $target_file)){
                    //Deleting the old picture
                    if($delete){
                        $oldFile = DB::getConfig()['image'];
                        unlink($oldFile);
                    }
                    return $target_file;
                }else
                    return "error";
                die();
            }
            public static function handleAdd(){
                $conn = DB::connect();
                $stmt = $conn->prepare("INSERT INTO projects (name,description,image,date,tags) VALUES (?,?,?,NOW(),?)") or die(mysqli_error($conn)); 
                $image = DB::handleImageUpload($_FILES['image'],false);
                $stmt->bind_param("ssss",$_POST['name'],$_POST['description'],$image,$_POST['tags']);
                $stmt->execute() or die("error : <br>" . $conn->error );
                header('Location: ../admin/');
            }
            public static function handleProjectUpdate(){
                $conn = DB::connect();
                $id = $_POST['id'];
                $name = $_POST['name'];
                $description = $_POST['description'];
                $tags = $_POST['tags'];
                if(isset($_FILES['image'])){
                    $image = DB::handleImageUpload($_FILES['image'],false);
                    $query = "UPDATE projects SET name = '$name', description = '$description', tags = '$tags', image = '$image' WHERE id = $id ";
                }else
                   $query = "UPDATE projects SET name = '$name', description = '$description', tags = '$tags' WHERE id = $id ";
                
                $res = $conn->query($query) or die($conn->error);
                header("Location: ../admin/");
            }
            public static function checkLogin($email,$password){
                $conn = DB::connect();
                $res = $conn->query("SELECT * FROM admins WHERE email = '$email'");
                if($res->num_rows == 0) return false;
                while($row = $res->fetch_assoc()){
                    if(password_verify($password,$row['password'])) return $row;
                }
                return false;
            }
        }

?>