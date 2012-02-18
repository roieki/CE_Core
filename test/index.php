<html>
    <head>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
        <script src="jquery.cookie.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {

            });

            function setJsonCookie(cookieName,object){
                $data = JSON.stringify(object);
                $.cookie('combined_test_data',$data, { path: '/' });
            }

        </script>
    </head>
    <body>

    </body>
</html>