<html>
    <head>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

        <script type="text/javascript" src="front.js"></script>
        <style>
            body{
                font-family: arial;
            }
            .tagList{
                float: left;

            }
            .results{
                float: right;
            }
            .entry{

                padding: 20px;
                marign: 5px;
                margin-bottom: 10px;
                -webkit-box-shadow: inset 0px -3px 1px rgba(0, 0, 0, 0.45), 0px 2px 2px rgba(0, 0, 0, 0.25);
                -moz-box-shadow: inset 0px -3px 1px rgba(0, 0, 0, 0.45), 0px 2px 2px rgba(0, 0, 0, 0.25);
                box-shadow: inset 0px -3px 1px rgba(0, 0, 0, 0.45), 0px 2px 2px rgba(0, 0, 0, 0.25);
                -webkit-border-radius: 3px;
                -moz-border-radius: 3px;
                border-radius: 3px;
                text-shadow: 1px 1px 0px rgba(0, 0, 0, 0.5);
            }
            .entry_name{
                font-size: 23px;
            }
            .button{
                padding: 7px;
                margin: 5px;
                -webkit-box-shadow: inset 0px -3px 1px rgba(0, 0, 0, 0.45), 0px 2px 2px rgba(0, 0, 0, 0.25);
                -moz-box-shadow: inset 0px -3px 1px rgba(0, 0, 0, 0.45), 0px 2px 2px rgba(0, 0, 0, 0.25);
                box-shadow: inset 0px -3px 1px rgba(0, 0, 0, 0.45), 0px 2px 2px rgba(0, 0, 0, 0.25);
                -webkit-border-radius: 3px;
                -moz-border-radius: 3px;
                border-radius: 3px;
                text-shadow: 1px 1px 0px rgba(0, 0, 0, 0.5);
                cursor: pointer;

            }
            .button.silver {
            background: #c4c4c4;
            background: #c4c4c4 -webkit-gradient( linear, 0% 0%, 0% 100%, from(rgba(255,255,255,.4)),to(rgba(0,0,0,0)));
            background: #c4c4c4 -moz-linear-gradient( top, rgba(255,255,255,.4), rgba(0,0,0,0));
            }
            .button.silver:hover {
            background: #c4c4c4 -webkit-gradient(linear, 0% 0%, 0% 100%, from(rgba(255,255,255,.55)), to(rgba(0,0,0,0)));
            background: #c4c4c4 -moz-linear-gradient( top, rgba(255,255,255,.55), rgba(0,0,0,0));
            }
            .button.silver:active {
            background: #c4c4c4 -webkit-gradient(linear, 0% 0%, 0% 100%, from(rgba(0,0,0,.3)), to(rgba(0,0,0,0)));
            background: #c4c4c4 -moz-linear-gradient( top, rgba(0,0,0,.1), rgba(0,0,0,0));
            }

            .button.green {
                        background: #33992F;
                        background: #33992F -webkit-gradient( linear, 0% 0%, 0% 100%, from(rgba(255,255,255,.4)),to(rgba(0,0,0,0)));
                        background: #33992F -moz-linear-gradient( top, rgba(255,255,255,.4), rgba(0,0,0,0));
                        }
                        .button.green:hover {
                        background: #33992F -webkit-gradient(linear, 0% 0%, 0% 100%, from(rgba(255,255,255,.55)), to(rgba(0,0,0,0)));
                        background: #33992F -moz-linear-gradient( top, rgba(255,255,255,.55), rgba(0,0,0,0));
                        }
                        .button.green:active {
                        background: #33992F -webkit-gradient(linear, 0% 0%, 0% 100%, from(rgba(0,0,0,.3)), to(rgba(0,0,0,0)));
                        background: #33992F -moz-linear-gradient( top, rgba(0,0,0,.1), rgba(0,0,0,0));
                        }
            .button.red {
                        background: #EB6565;
                        background: #EB6565 -webkit-gradient( linear, 0% 0%, 0% 100%, from(rgba(255,255,255,.4)),to(rgba(0,0,0,0)));
                        background: #EB6565 -moz-linear-gradient( top, rgba(255,255,255,.4), rgba(0,0,0,0));
                        }
                        .button.red:hover {
                        background: #EB6565 -webkit-gradient(linear, 0% 0%, 0% 100%, from(rgba(255,255,255,.55)), to(rgba(0,0,0,0)));
                        background: #EB6565 -moz-linear-gradient( top, rgba(255,255,255,.55), rgba(0,0,0,0));
                        }
                        .button.red:active {
                        background: #EB6565 -webkit-gradient(linear, 0% 0%, 0% 100%, from(rgba(0,0,0,.3)), to(rgba(0,0,0,0)));
                        background: #EB6565 -moz-linear-gradient( top, rgba(0,0,0,.1), rgba(0,0,0,0));
                        }

        </style>
    </head>

    <body>
        <div class="controls">

        </div>
        <div class="tagList">
            <div class="tagList_header">Tags</div>
            <div class="tagList_content"></div>
        </div>
        <div class="results">

        </div>
    </body>
</html>