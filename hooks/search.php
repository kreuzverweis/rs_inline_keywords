<?php
    function HookInline_keywordsSearchSearchbarbottomtoolbar()
    {
        global  $baseurl,$lang, $inline_keywords_usertype;
        if(checkperm($inline_keywords_usertype))
            {
            ?>
            <div id="SearchBoxPanel" class="keywordPanel">
              <div class="SearchSpace">
                <h2><?php echo $lang["editfields"]; ?></h2>
                <p><?php echo $lang['keywordstoresource']; ?></p>
                
                <form id="manipulateKeywords">                                    
                  <span class="wrap">
                    <p>
                        <label for='newKeywordsForSelectedResources'>Keywords</label>
                        <input id='newKeywordsForSelectedResources' class='SearchWidth'/>
                    </p>
                  </span>
                  <div id="skmessages"></div>                        
                  <div id="skproposals">    
                      <img src="<?php echo $baseurl ?>/plugins/smartkeywording_rs/images/ui-anim_basic_16x16.gif" style="position:absolute;padding:0px;margin:5px;line-height:10px;top:0px;right:0px;display:none;" id="loadingDiv">                                                       
                  </div>                                                                                
                  <input type="button" id="selectAllResourceButton" value="<?php echo $lang["selectall"]; ?>">
                  <input type="button" id="clearSelectedResourceButton" value="<?php echo $lang["unselectall"]; ?>">
                  <input type="button" id="submitSelectedResourceButton" value="<?php echo $lang["submitchanges"]; ?>">
                </form>
              </div>
            </div>
            <?php
        }
    }
    function HookInline_keywordsSearchAdditionalheaderjs()
        {
        global $baseurl, $inline_keywords_usertype, $inline_keywords_background_colour;
    if(checkperm($inline_keywords_usertype))
        { ?>

            <link type="text/css" href="<?php echo $baseurl?>/plugins/smartkeywording_rs/css/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
            <script src="<?php echo $baseurl?>/plugins/smartkeywording_rs/js/jquery-ui.min.js" type="text/javascript"></script>    
            <script src="<?php echo $baseurl?>/plugins/smartkeywording_rs/js/jquery.cookie.js" type="text/javascript"></script>
            <script src="<?php echo $baseurl?>/plugins/smartkeywording_rs/js/jquery.tools.min.js" type="text/javascript"></script>
            <script src="<?php echo $baseurl?>/plugins/smartkeywording_rs/js/bootstrap-alerts.js" type="text/javascript"></script>
            <script src="<?php echo $baseurl?>/plugins/smartkeywording_rs/js/bootstrap-buttons.js" type="text/javascript"></script>
            <script src="<?php echo $baseurl?>/plugins/smartkeywording_rs/js/bootstrap-twipsy.js" type="text/javascript"></script>
            <script src="<?php echo $baseurl?>/plugins/smartkeywording_rs/js/bootstrap-popover.js" type="text/javascript"></script>
            <script src="<?php echo $baseurl?>/plugins/smartkeywording_rs/js/kreuzverweis.ui.delayedExec.js" type="text/javascript"></script>        
            <script src="<?php echo $baseurl?>/plugins/smartkeywording_rs/js/kreuzverweis.ui.messages.js" type="text/javascript"></script>
            <script src="<?php echo $baseurl?>/plugins/smartkeywording_rs/js/kreuzverweis.smartkeywording.webgui.js" type="text/javascript"></script>
            <script src="<?php echo $baseurl?>/plugins/smartkeywording_rs/js/kreuzverweis.sk.rs.js" type="text/javascript"></script>
            <script src="<?php echo $baseurl?>/plugins/inline_keywords/js/kreuzverweis.sk.inline.js" type="text/javascript"></script>
            <script src="../plugins/inline_keywords/js/jquery.infieldlabel.min.js" type="text/javascript" charset="utf-8"></script>
            <script type='text/javascript'>
                jQuery(document).ready(function() {
                    
                    addSKAutocomplete("newKeywordsForSelectedResources");                    
                    addSKProposalsOnReturn("newKeywordsForSelectedResources","skproposals");                    
                    addSKSelection("skproposals");
                    
                    jQuery('form#manipulateKeywords :text').focus(function(event){
                        jQuery(this).siblings('label').fadeOut('fast');
                    });

                    jQuery('form#manipulateKeywords :text').blur(function(event){
                        if(jQuery(this).val() === ""){
                            jQuery(this).siblings('label').fadeIn('fast');                            
                        }
                    });
                    
                    jQuery('.ResourcePanelShell, .ResourcePanelShellSmall').on('click', function(event) {
                        if(!(event.originalEvent.srcElement instanceof HTMLImageElement )){
                            //console.log(event.originalEvent.srcElement instance of HTMLImageElement);
                            jQuery(this).toggleClass('chosen');
                            jQuery('.ResourcePanel, .ResourcePanelSmall').css('border-color','');
                            jQuery('.chosen .ResourcePanel, .chosen .ResourcePanelSmall').css('border-color','<?php echo $inline_keywords_background_colour; ?>');
                        }
                    });
                    
                    jQuery('#clearSelectedResourceButton').on('click', function() {
                        jQuery('.chosen').removeClass('chosen');
                        jQuery('#newKeywordsForSelectedResources').val('');
                        jQuery('.ResourcePanel, .ResourcePanelSmall').css('border-color','');
                        jQuery('.chosen .ResourcePanel, .chosen .ResourcePanelSmall').css('border-color','<?php echo $inline_keywords_background_colour; ?>');
                    });
                    
                    jQuery('#selectAllResourceButton').on('click', function() {
                        jQuery('.ResourcePanelShell, .ResourcePanelShellSmall').addClass('chosen');
                        jQuery('.ResourcePanel, .ResourcePanelSmall').css('border-color','');
                        jQuery('.chosen .ResourcePanel, .chosen .ResourcePanelSmall').css('border-color','<?php echo $inline_keywords_background_colour; ?>');
                    });
                    
                    jQuery('#submitSelectedResourceButton').on('click', function() {
                        resourceIds = jQuery.map(jQuery('.chosen'), function(a, b){
                            return jQuery(a).attr('id').replace('ResourceShell','');
                        }).join('+');
                        var selectedKeywords=getSelectedKeywords("skproposals");
                        jQuery.ajax({
                            type: "POST",
                            url: "<?php echo $baseurl; ?>/plugins/inline_keywords/pages/add_keywords.php",
                            data: { refs: resourceIds, keywords:  selectedKeywords}
                        }).done(function( msg ) {
                            getProposals(0, selectedKeywords, function() {
                                jQuery("#skproposals").empty();
                                addProposals(this);
                            })
                            if(msg !== ''){alert( "Data Saved: " + msg );}
                            //jQuery(".keywordPanel").effect("highlight", {}, 3000);
                            jQuery(".keywordPanel").fadeTo("slow", 0.5, function () {
                                jQuery(".keywordPanel").fadeTo("slow", 1.0, function(){});
                            });
                        });
                    });
                });
            </script>
        <?php }
    return true;
    }
?>