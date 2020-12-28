<!DOCTYPE html>
<html lang="en">
<title>EasyAuth licensing</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="style.css">
<body>

<!-- Top header-->
<div style="border-bottom: 1px solid black">
    <header class="w3-container w3-xlarge">
        <p class="w3-right">
            <i class="fa fa-user-circle" style ="font-size: 35px;"></i>
            <i>username</i>
            <i class="fa fa-sort-down"></i>
        </p>
    </header>
</div>

<!-- Main content -->
<button style ="background-color: #EE494A; margin-left: 80%; margin-top: 3%" class ="w3-large">Upload your Software here</button>
<div style="margin-left:18%; margin-top:7%" class="w3-responsive w3-container">
    <table class="w3-table w3-bordered w3-border w3-centered">
       <thead class ="w3-xlarge">
           <tr>
                <th> </th>
                <th>Product xyz</th>
                <th> </th>
            </tr>
     </thead>
     <tr>
            <th>License</th>
            <th>HWID</th>
            <th>Duration</th>
        </tr>
        <tr>
            <td>test</td>
            <td>test</td>
            <td>69 days remaining</td>
        </tr>
        <tr>
            <td>another test</td>
            <td>another test</td>
            <td>88 days remaining</td>
        </tr>
    </table>
    <div style="text-align: center; margin-top: 1%" class="w3-large">
        <button style="margin-right: 5%; background-color: #EE494A">Add</button>
        <button style="margin-right: 5%; background-color: #EE494A"">Modify</button>
        <button style="margin-right: 5%; background-color: #EE494A"">Delete</button>
    </div>
</div>
<!-- Sidebar/menu -->
<nav class="w3-sidebar w3-bar-block w3-top w3-large" style="z-index:3;width:18%;font-weight:bold; background-color: #222222;" id="mySidebar"><br>
    <div style="padding-left: 35px; padding-bottom: 30px;" class="w3-container">
        <img src="moga_logo.png" alt="logo" style="width: 230px;height: 130px;">
    </div>
    <div id= "myButtons" class="w3-bar-block" style="font-size: x-large;">
        <a id = "dashboard-page" href="dashboard.php" onclick="w3_close()" style="color: whitesmoke; text-align: center;" class="w3-bar-item w3-button w3-hover-red btn">Dashboard</a>
        <div class ="w3-dropdown-hover ">
            <a id = "products-page" href="products.php" onclick="w3_close()" style="color: whitesmoke; text-align: center;" class="w3-bar-item w3-button w3-hover-red btn">Products</a>
            <div class="w3-dropdown-content w3-bar-block w3-card-4">
                <a href="xyz" style="background-color: #222222; color: whitesmoke; padding-bottom: 10px; text-align: center;" class ="w3-bar-item w3-button w3-hover-red">product xyz</a>
                <a href="123" style="background-color: #222222; color: whitesmoke; padding-bottom: 10px; text-align: center;" class ="w3-bar-item w3-button w3-hover-red">product 123</a>
            </div>
        </div>
        <a id = "news-page" href="news.php" onclick="w3_close()" style="color: whitesmoke; text-align: center;" class="w3-bar-item w3-button w3-hover-red btn">News</a>
        <a id = "support-page" href="support.php" onclick="w3_close()" style="color: whitesmoke; text-align: center;" class="w3-bar-item w3-button w3-hover-red btn">Support</a>
    </div>
    <a href="javascript:void(0)" onclick="w3_close()" class="w3-button w3-hide-large w3-display-bottom" style="100%;font-size:22px;">Close Menu</a>
</nav>

<!-- Top menu on small screens -->
<header class="w3-container w3-top w3-hide-large w3-red w3-xlarge w3-padding">
    <a href="javascript:void(0)" class="w3-button w3-red w3-margin-right" onclick="w3_open()">☰</a>
    <span>EasyAuth</span>
</header>

<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<div class="w3-main" style="margin-left:340px;margin-right:40px">

    <script>
        function w3_open() {
            document.getElementById("mySidebar").style.display = "block";
            document.getElementById("myOverlay").style.display = "block";
        }

        function w3_close() {
            document.getElementById("mySidebar").style.display = "none";
            document.getElementById("myOverlay").style.display = "none";
        }

        function onClick(element) {
            document.getElementById("img01").src = element.src;
            document.getElementById("modal01").style.display = "block";
            var captionText = document.getElementById("caption");
            captionText.innerHTML = element.alt;
        }

    </script>

</body>
</html>