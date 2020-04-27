<?php
    session_start();
?>
<!doctype html>
<html lang="en">
<?php
   require_once 'checkSession.php';
?>
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" href="post.css">
        <script
      src="https://use.fontawesome.com/releases/v5.12.1/js/all.js"
      data-auto-a11y="true"
    ></script>
        <title>Post Page</title>
    </head>
    <body>
        <nav class="navbar fixed-top navbar-expand-lg navbar-dark " id="nav-bg">
            <a class="navbar-brand" href="#"><i class="fas fa-bug"></i></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse justify-content-between" id="navbarTogglerDemo01">
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                <li class="nav-item">
                <a class="nav-link" href="account.php">Account </a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="matches.php">Matches</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="messages.php">Messages</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="#">Post<span class="sr-only">(current)</span></a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user-cog"></i></a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                      <a class="dropdown-item" href="info.php">Edit Information</a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="Verify Delete.php">Delete Account</a>
                    </div>
                </li>
                <li class="nav-item">
                    <button id="out_btn" class="btn my-2 my-sm-0" type="button" onclick="location.href = 'logout.php';">Log Out</button>
                </li>
            </ul>
            </div>
        </nav>

        <div class="cent">
            <br>
            <h2>Posts</h2>
            <ul class="list-group" id="post_list"></ul>
            <hr>
        </div>

        <div class="container">
            <div class=" row c-row justify-content-center align-items-center">
                <form class="form-inline" action="sendPost.php" method="post">
                    <label for="courseOffer" class="mr-sm-2">Offer:</label>
                    <input type="text" class="form-control mb-2 mr-sm-2" placeholder="CSE 442" id="courseOffer" name="courseOffer">
                    <label for="courseRequest" class="mr-sm-2">Request:</label>
                    <input type="text" class="form-control mb-2 mr-sm-2" placeholder="MTH 309" id="courseRequest" name="courseRequest">
                    <button type="submit" id="sub_btn" value="SUBMIT "class="btn btn-primary mb-2">Make Post</button>
                </form> 
            </div>
        </div>

        <?php
            require_once 'mysql.php';
            require_once 'session.php';
            session_start();
            $usr_id = $_SESSION["userId"];
            $sql = "SELECT courseOffer, courseRequest FROM swapRequest WHERE userId = '$usr_id'";
            $conn = get_connection();
            $results = query_database($conn, $sql);
  
            if(mysqli_num_rows($results) > 0){
              $data = "";
                while($row = mysqli_fetch_assoc($results)){
                    $data .= $row["courseOffer"] . "+" . $row["courseRequest"];
                    $data .= "!";
                }
                $data  = substr($data, 0, -1);
            }else{
                $data = "";
            }
        ?>

        <script>
            makePost();
            function makePost(){
                var ul = document.getElementById("post_list");
                var sql = '<?php echo $data ;?>';
                if(sql===""){
                    return;
                }
                console.log(sql);
                var courses = sql.split("!");
                for(var i=0; i<courses.length; i++){
                    var arr = courses[i].split("+");
                    var courseOffer = arr[0];
                    var courseRequest = arr[1];
                    var days = arr[2];
                    var start = arr[3];
                    var end = arr[4];
                    var location = arr[5];
                    var post = courseOffer + " --> " + courseRequest;
                    var li = document.createElement("li");
                    li.appendChild(document.createTextNode(post));
                    li.setAttribute("class", "list-group-item"); 
                    ul.appendChild(li);
                }
            }
        </script>
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    </body>
</html>