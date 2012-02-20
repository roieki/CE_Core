<html>
    <head>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
        <script type="text/javascript">
            <?php
                include('back.php');
                $combinedTags = getAllTags(false);

            ?>
            var combinedTagsJSON = JSON.parse('<?=getAllTags()?>');


        </script>
        <style>
            .control{

            }

            .control.connect_target{
                display: none;
            }

            .tag{
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

            .tag_controls{
                float: right;
            }

            .tag_name{
                font-size: 23px;

            }

            .tags{
                width: 40%;
                float: left;
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
                color: #B8F500;
                background: #33992F;
                background: #33992F -webkit-gradient( linear, 0% 0%, 0% 100%, from(rgba(255,255,255,.4)),to(rgba(0,0,0,0)));
                background: #33992F -moz-linear-gradient( top, rgba(255,255,255,.4), rgba(0,0,0,0));
            }
            .button.green:hover {
                color:#DBFF70;
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

            .console{
                background-color: #A3D1FF;
                width: 40%;
                float: right;
                min-height: 80%;
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

        </style>

        <script type="text/javascript" src="front.js"></script>

    </head>
    <body>
    <div class="tags">
        <?php foreach($combinedTags as $tag):?>
        <div class="tag">
            <span class="tag_name"><?=$tag['value']?></span>
            <span class="tag_controls" entryid='<?=$tag['id']?>'>
                <span class="control button green" action="rename">Rename</span>
                <span class="control button green" action="explore">Explore</span>
                <span class="control button green" action="connect">Connect</span>
                <span class="control button connect_target" action="connect">
                    <span class="control ">
                        <span class='manualTag'>
                            <input class='manualTagInput' type='text'></>
                            <input type='hidden' class='manualTagInput-id' />
                            <span class='manualTagInputSubmit button'>Submit</span></span>
                    </span>
                </span>

            </span>
        </div>
        <?php endforeach;?>
    </div>
    <div class="console">

    </div>
    </body>
</html>