<?php
require_once('cavein/cleandata.php');
$nicedate = '2017-11-24';
date_default_timezone_set("Asia/Kathmandu");
if(date('H')>=8){

    $nicedate =date('Y-m-d');

}

$thispage="searchresult";

?>

<?php
function daysRemaining($data){
    
    $d1=strtotime($data);
    $d2=ceil(($d1-time())/60/60/24);
    if($d2<0){
        return 'Expired';
    }else{
        return $d2.' Day/s Left';
    }
}

?>
<?php
    require_once('cavein/connect.php');
    $dbc=mysqli_connect($host, $user, $pass, $dbname) or die("Couldn\'t connect to database.");
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Search Result</title>
    <link rel="icon" type="image/png" sizes="16x16" href="img/noticenepalfav.png">
    <link href="styles/normalize.css" rel="stylesheet" type="text/css">
    <link href="styles/style.css" rel="stylesheet" type="text/css">
    
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-125185088-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-125185088-1');
    </script>

    
    <!-- bootstrab cdn -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
    
    
    <!-- fontawesome cdn -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.9/css/all.css" integrity="sha384-5SOiIsAziJl6AWe0HWRKTXlfcSHKmYV4RBF18PPJ173Kzn7jzMyFuTtk8JA7QQG1" crossorigin="anonymous">
    
    
    <script src = "https://code.jquery.com/jquery-1.10.2.js"></script>

    <style>
        body{
            background-color:aliceblue;
        }
    </style>
    </head>
    
    <body>
     
      
      <?php include_once('nav.php'); ?>
       <div class="container-fluid">
            <h2 class="text-center" style="margin-top:7rem;margin-bottom:1rem;text-shadow:-2px 1px 1px #0a5a1c;color:#287da7!important;">Your Search Result ! </h2>

<?php
           
$queryString='';


if(isset($_GET['description']) && !empty($_GET['description'])){
        $description=datacleaner($_GET['description']);
            $queryString .= ' description=\''.$description.'\' AND';
    
            
        }
        
        if(isset($_GET['category']) && $_GET['category'] != "default"){
        $category=datacleaner($_GET['category']);    
            $queryString .= ' category=\''.$category.'\' AND';
            
            
        }
        
        if(isset($_GET['subcategory'])&& $_GET['subcategory'] != "default"){
        $subcategory=datacleaner($_GET['subcategory']);    
            $queryString .= ' subcategory=\''.$subcategory.'\' AND';
            
            
        }
        
        if(isset($_GET['newspaper'])&& $_GET['newspaper'] != "default"){
        $newspaper=datacleaner($_GET['newspaper']);    
            $queryString .= ' newspaper=\''.$newspaper.'\' AND';
            
            
        }
        
        if(isset($_GET['pickeddate'])&& !empty($_GET['pickeddate'])){
        $pickeddate=datacleaner($_GET['pickeddate']);    
            $queryString .= ' date=\''.$pickeddate.'\' AND';
            
            
        }


$explodeString = explode(" ", $queryString);

//removing the last word "AND" from the query string
$removeWord= array_pop($explodeString);

$str1= implode(" ", $explodeString);


//now connecting to the database and selecting the //data based on the query string created

$dbc = mysqli_connect($host, $user, $pass, $dbname) or die("Couldn\'t connect to the database.");


/*pagination code starts */
    
    $query1 = "SELECT COUNT(*) FROM content WHERE". $str1;
           echo $query1;
    
    $result=mysqli_query($dbc, $query1)or die('Couldn\'t fetch the data from the database');

    $r= mysqli_fetch_row($result);
    $numrows=$r[0];
    if($numrows>15){
       //number of rows to show per page
        $rowsperpage = 15;
        //finding total number of pages
        $totalpages= ceil($numrows/$rowsperpage);
        
            //get the current page or set to default
            if (isset($_GET['currentpage']) && is_numeric($_GET['currentpage'])) {
            // cast var as int
            $currentpage = (int) $_GET['currentpage'];
            } else {
            // default page num
            $currentpage = 1;
            } // end if
        
            // if current page is greater than total pages...
            if ($currentpage > $totalpages) {
               // set current page to last page
               $currentpage = $totalpages;
            } // end if
        
            // if current page is less than first page...
            if ($currentpage < 1) {
               // set current page to first page
               $currentpage = 1;
            } // end if
    
            // the offset of the list, based on current page 
            $offset = ($currentpage - 1) * $rowsperpage;

            // get the info from the db 
            
           // include_once('displayNotice.php');
        // get the info from the db 
        echo $str1;
            $query1 = "SELECT * FROM content WHERE $str1 AND lastDate>='$nicedate' LIMIT $offset, $rowsperpage";
            
    
            $result=mysqli_query($dbc, $query1)or die('Couldn\'t fetch the data from the database');
            if($result)
               { 
                   
                   
            echo  '<div class="table-responsive">';
               echo '<table class="table table-striped table-bordered table-condensed">';
               echo '<tr style="font-size:0.85rem;">';
                   
                   echo '<th>S.No</th>';
                   echo '<th>Description</th>';

                   echo '<th>Publisher</th>';
                   echo '<th>Category</th>';
                   echo '<th>Sub-Category</th>';
                   echo '<th>Published In</th>';
                   echo '<th>Published On</th>';
                   echo '<th>Last Date</th>';
                   echo '<th>Day/s Remaining</th>';
                   
                   echo '<th>Image</th>';
               echo '</tr>';
            $sno=1;
                   while($row=mysqli_fetch_array($result)){
    
    
                echo '<tr>';
            
                echo '<td>'.$sno.'</td>';
                echo '<td>'.$row['description'].'</td>';
                echo  '<td>'.$row['publisher'].'</td>'; 
                echo  '<td>'.$row['category'].'</td>';
                echo  '<td>'.$row['subcategory'].'</td>';
                echo  '<td>'.$row['newspaper'].'</td>';
                echo  '<td>'.$row['date'].'</td>';
                echo  '<td>'.$row['lastDate'].'</td>';
                echo  '<td>'.daysRemaining($row['lastDate']).'</td>';       
                echo  '<td><img src="cavein/imgs/'.$row['uimg'].'" style="height:50px;width:50px;" class="imgThumb" /></td>';
                
                echo '</tr>';
                    $sno++;
                   }
                
                   mysqli_free_result($result);
                   mysqli_close($dbc);
                
                echo '</table>';
                   echo '</div>';
               } 
               else { 
                   echo '<p> Nothing found ! </p>';
                     
                     } 
        
        
    /******  build the pagination links ******/
        // range of num links to show
        $range = 3;
        echo "<div style='display:flex; justify-content:center; margin-bottom:2rem;' >";
        echo "<div class='btn-group'>";
        // if not on page 1, don't show back links
        if ($currentpage > 1) {
           // show << link to go back to page 1
           echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=1' class='btn btn-primary'><<</a> ";
           // get previous page num
           $prevpage = $currentpage - 1;
           // show < link to go back to 1 page
           echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$prevpage' class='btn btn-primary'><</a> ";
        } // end if 

        // loop to show links to range of pages around current page
        for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
           // if it's a valid page number...
           if (($x > 0) && ($x <= $totalpages)) {
              // if we're on current page...
              if ($x == $currentpage) {
                 // 'highlight' it but don't make a link
                 echo " <span class='btn btn-secondary disabled'>$x</span> ";
              // if not current page...
              } else {
                 // make it a link
                 echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$x' class='btn btn-primary'>$x</a> ";
              } // end else
           } // end if 
        } // end for

        // if not on last page, show forward and last page links        
        if ($currentpage != $totalpages) {
           // get next page
           $nextpage = $currentpage + 1;
            // echo forward link for next page 
           echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$nextpage' class='btn btn-primary'>></a> ";
           // echo forward link for lastpage
           echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$totalpages' class='btn btn-primary'>>></a> ";
        } // end if
        echo "</div>";
        echo "</div>";
        /****** end build pagination links ******/           
               
    
    
    }elseif($numrows>0){
        // get the info from the db 
            $offset =0;
            $rowsperpage=15;
            include_once('displayNotice.php');
    }else{
        echo 'No data available to display !';
    }//end of first if statement

    
?>
<!-- modal for image viewing  -->
<div class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">NoticeNepal.Com</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
        <div class="modal-body">
            <img src="" class="img-responsive" style="width:100%" />
        </div>
    </div>
</div>
</div>
</div><!-- end of fluid-container -->
       
       
           
<script>
  
      $("body").on("click", ".imgThumb",function(event){
     
         event.preventDefault();
         $(".modal img").attr("src", $(this).attr("src"));
         $(".modal").modal("show");
     }); 
</script>       
       
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"></script>

<?php include_once('footer.php'); ?>
             </div>
       <!-- footer divclosed -->
       </body>
</html>
