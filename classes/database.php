<?php
 
class database {
    function opencon(): PDO {
       return new PDO(dsn: 'mysql:host=localhost;
       dbname=lms_app.sql',
       username: 'root',
       password: '');
    }
 
    function signupUser($firstname, $lastname, $birthday, $email, $sex, $phone, $username, $password, $profile_picture_path) {
        $con = $this->opencon();
        try {
            $con->beginTransaction();
           
            // Insert into Users table
            $stmt = $con->prepare("INSERT INTO Users (user_FN, user_LN, user_birthday, user_sex, user_email, user_phone, user_username, user_password) VALUES (?, ?, ?, ? ,? ,?, ?, ?)");
            $stmt->execute([$firstname, $lastname, $birthday, $sex,
            $email, $phone, $username, $password]);
   
            //Get newly inserted user id
            $userId = $con->lastInsertId();
   
            // Insert into users_pictures table
            $stmt = $con->prepare("INSERT INTO users_pictures (user_id, user_pic_url) VALUES (?, ?)");
            $stmt->execute([$userId, $profile_picture_path]);
   
            $con->commit();
            return $userId;
        } catch (PDOException $e) {
            $con->rollBack();
            return false;
        }
   
    }//signupUser end
 
    function insertAddress($userID, $street, $barangay, $city, $province) {
        $con = $this->opencon();
        try {
            $con->beginTransaction();
   
            // Insert into Address table
            $stmt = $con->prepare("INSERT INTO Address (ba_street,
            ba_barangay, ba_city, ba_province) VALUES (?, ?, ?, ?)") ;
            $stmt->execute([$street, $barangay, $city, $province]);
   
            // Get the newly inserted address_id
            $addressId = $con->lastInsertId();
 
            //Link User and Address into Users_Address table
            $stmt = $con->prepare("INSERT INTO Users_Address (user_id, address_id) VALUES (?, ?)");
            $stmt->execute([$userID, $addressId]);
 
            $con->commit();
            return true;
        } catch (PDOException $e) {
            $con->rollBack();
            return false;
        }
    }//insertAddress end
 
    function loginUser($email, $password) {
        $con = $this->opencon();
        $stmt = $con->prepare("SELECT * FROM users WHERE user_email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
 
        if ($user && password_verify($password, $user['user_password'])) {
            return $user;
        } else {
            return false;
        }
    }

     function addAuthor($author_FN, $author_LN, $author_Bday, $author_Nation) {
        $con = $this->opencon();
        try {
            $con->beginTransaction();
           
            // Insert into Users table
            $stmt = $con->prepare("INSERT INTO Authors (author_FN, author_LN, author_birthday, author_nat) VALUES (?, ?, ?, ?)");
            $stmt->execute([$author_FN, $author_LN, $author_Bday, $author_Nation]);
   
            //Get newly inserted user id
            $authorId = $con->lastInsertId();
   
            
            $con->commit();
            return $authorId;
        } catch (PDOException $e) {
            $con->rollBack();
            return false;
        }
    }
         function addGenres($genreName) {
        $con = $this->opencon();
        try {
            $con->beginTransaction();
           
            // Insert into Users table
            $stmt = $con->prepare ("INSERT INTO genres (genre_name) VALUES (?)");
            $stmt->execute([$genreName]);
   
            //Get newly inserted user id
            $genreId = $con->lastInsertId();
   
            
            $con->commit();
            return $genreId;
        } catch (PDOException $e) {
            $con->rollBack();
            return false;
        }
}
}
