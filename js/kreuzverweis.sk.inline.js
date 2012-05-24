/**

 Copyright 2012 Kreuzverweis Solutions GmbH

 Licensed under the Apache License, Version 2.0 (the "License");
 you may not use this file except in compliance with the License.
 You may obtain a copy of the License at

 http://www.apache.org/licenses/LICENSE-2.0

 Unless required by applicable law or agreed to in writing, software
 distributed under the License is distributed on an "AS IS" BASIS,
 WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 See the License for the specific language governing permissions and
 limitations under the License.

 * @author Dr. Thomas Franz
 */

function addProposals(proposals) {
	jQuery.each(proposals, function(index, value) {
		var addedKeyword = jQuery('<span>').attr("class", "skKeyword");
		addedKeyword.text(value);
		jQuery("#skproposals").append(addedKeyword);
	});
}

function addSKProposalsOnReturn(fieldId, proposalContainerId) {
	jQuery('#' + fieldId).bind('keypress', function(e) {
		var code = (e.keyCode ? e.keyCode : e.which);
		// if ENTER is pressed
		if(code == 13) {
			if(jQuery(this).val() != "") {
				jQuery('#' + fieldId).autocomplete("close");
				var addedKeyword = jQuery('<span>').attr("class", "skKeyword skSelected");
				addedKeyword.text(jQuery(this).val());
				jQuery("#skproposals").append(addedKeyword);
				jQuery(this).val("");
				jQuery("#loadingAnimation").show();
				getProposals(0, getSelectedKeywords(proposalContainerId), function() {
					jQuery("#loadingAnimation").hide();
					addProposals(this);
				})
				return false;
			}
		}
	});
}

function addSKSelection(containerId) {
	jQuery("#" + containerId + " > span.skKeyword").live('click', function() {
		jQuery(this).toggleClass("skSelected");
	});
}

function getSelectedKeywords(proposalContainerId) {
	// collect currently selected keywords
	var selectedKeywords = "";
	jQuery.each(jQuery("#" + proposalContainerId + " > span.skSelected"), function(index, value) {
		if(selectedKeywords)
			selectedKeywords = selectedKeywords + "," + value.textContent;
		else
			selectedKeywords = value.textContent;
	});
	return selectedKeywords;
}
