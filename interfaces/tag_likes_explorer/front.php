<html>
    <head>
        <link href="../css/ui-lightness/jquery-ui-1.8.17.custom.css" rel="stylesheet">
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
        <script type="text/javascript">
            <?php
                include('back.php');
                $combinedTags = getAllTags(false);
            ?>

            var combinedTagsJSON = JSON.parse('<?=getAllTags()?>');
            var combinedTagsJSONencoded = [];
            for (index in combinedTagsJSON){
                combinedTagsJSONencoded.push(decodeURI(combinedTagsJSON[index].value).replace("+"," "));
            }
            var forumList = '<?php echo get_external_categories('fxp','fxp_forums_list');?>';
            var forumListJson = JSON.parse(forumList);
            var forumListJsonencoded = [];
            for (index in forumListJson){
                forumListJsonencoded.push(decodeURI(forumListJson[index].value).replace("+"," "));
            }

            var facebookCategories = '<?php echo getFacebookCategories();?>';
            var facebookCategoriesJson = JSON.parse(facebookCategories);



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
                width: 45%;
                float: left;
            }

            .manual{
                width: 40%;
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

            .relations_control{
                clear: both;
                width: 100%;
                margin-top: 30px;
                display: none;

            }
            .relation_option{
                display: none;
            }

        </style>

        <script type="text/javascript" src="front.js"></script>

    </head>
    <body>
    <div class="manual">
        <span class="manual_controls" action="connect">
            <span>Manual Tag</span>
            <span class="control">
                <span class='manualTag'>
                    <input class='manualTagInput' type='text'></>
                    <input type='hidden' class='manualTagInput-id'/>
                    <span class='manualTagInputSubmit button green' action="newTag">Submit</span>
                </span>
            </span>
        </span>
    </div>

    <div class="tags">
        <?php foreach($combinedTags as $tag):?>
        <div class="tag">
            <div class="tag_data">
                <span class="tag_name"><?=urldecode($tag['value'])?></span>
                <span class="tag_controls" entryid='<?=$tag['id']?>'>
                    <span class="control button green" action="rename">Rename</span>
                    <span class="control button green" action="explore">Explore</span>
                    <span class="control button green" action="connect">Connect</span>
                    <span class="control button red" action="delete">Delete</span>
                    <span class="control button connect_target" action="connect">
                        <span class="control ">
                            <span class='manualTag'>
                                <input class='manualTagInput' type='text'></>
                                <input type='hidden' class='manualTagInput-id' />
                                <span class='manualTagInputSubmit button'>Submit</span>
                            </span>
                        </span>
                    </span>
                </span>
            </div>

            <div class="relations_control">
                <span class="relation_type_selector_wrapper">
                    <select class="relation_type_selector">
                        <option value="choose">Choose action</option>
                        <option value="mapping_controls">Mapping</option>
                        <option value="tags_controls">Tag relation</option>
                        <option value="fb_mapping_controls">Facebook Mapping</option>
                    </select>
                </span>
                <span class="mapping_controls relation_option">
                    <span class='mapping_controls_input_wrapper'>
                        <input class='mapping_controls_input' type='text'></>
                        <input type='hidden' class='mapping_controls_input-id' />
                        <span class='mapping_controls_input_submit button' action="updateMapping">Submit</span>
                    </span>
                </span>
                <span class="tags_controls relation_option">
                    <span class='tags_controls_input_wrapper'>
                        <span></span>
                        <select>
                            <option value="choose">Relation type</option>
                            <option value="choose">"<?=urldecode($tag['value'])?>" is a parent of </option>
                            <option value="choose">"<?=urldecode($tag['value'])?>" is a childe of </option>
                        </select>
                        <input class='tags_controls_input' type='text'></>
                        <input type='hidden' class='tags_controls_input-id' />
                        <span class='tags_controls_input_submit button' action="updateTagsRelations">Submit</span>
                    </span>
                </span>
                <span class="fb_mapping_controls relation_option">
                    <span class='fb_mapping_controls_input_wrapper'>
                        <input class='fb_mapping_controls_input' type='text'></>
                        <input type='hidden' class='fb_mapping_controls_input-id' />
                        <span class='fb_mapping_controls_input_submit button' action="updateFBMapping">Submit</span>
                    </span>
                </span>
            </div>

        </div>
        <?php endforeach;?>

    </div>
    <div class="console">

    </div>
    </body>
</html>