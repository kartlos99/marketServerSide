
<!DOCTYPE html>
<html>
<head>
    <title>Market</title>
    <!-- Bootstrap CSS -->
    <!-- Latest compiled and minified CSS -->
    <!--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
          integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <!-- Our Custom CSS -->
    <!-- <link rel="stylesheet" href="../style/sidebar-style.css"> -->
    <!-- Scrollbar Custom CSS -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">

    <link rel="stylesheet" href="form2.css">

    <style>
        textarea {
            background-color:antiquewhite;
            width: 80%;
            height: 120px;
            margin-left: 10%
        }
        #aExp {
            margin-left: 50%;
        }
        .showdata {
            width: 90%;
            margin-left: auto;
            margin-right: auto;
            background-color: #f1f1f1;
        }
    </style>

</head>
<body>



<textarea id="sqlInput" placeholder="Enter SQL Query"></textarea>

<a id="aExp" href="exportAll.php" class="btn btn-info">exp</a>

<button id='btn_run' class="btn btn-primary" >RUN</button>

<p><a href="main.php">Main Page</a></p>

<div class="showdata"></div>





<script src="https://code.jquery.com/jquery-3.2.1.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>
<!-- jQuery Custom Scroller CDN -->
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>
    
<script>
    $('#sqlInput').on('blur', function(){
        $('#aExp').attr('href', 'exportAll.php?sql='+$('#sqlInput').val());
    });

    $('#btn_run').on('click', function(){
        $('.showdata').html('hi');
        console.log('run');
        
        $.ajax({
            url: 'show_res.php?sql=' + $('#sqlInput').val(),
            method: 'get',
            dataType: 'text',
            success: function (response) {
                console.log('resp:');
                console.log(response);
                if (response != null && response != ''){
                    $('.showdata').html(response);
                }
            }

        });

        // $('.showdata table').addclass('datatable');

    })
</script>

</body>
</html>