<?php
/*
Plugin Name: Tags2Keywords
Plugin URI: mailto:ovsyannikov.ivan@gmail.com
Description: The plugin automatically adds keywords in heading from tags. Uses for this purpose one SQL-query as get_the_tags() works only in Loop. The 'Quick META Keywords' plug-in uses categories for keywords, this plug-in uses tags. The 'Tags2MetaKeywords' plug-in does not add automatically.
Version: 0.2
Author: Ivan Ovsyannikov
Author URI: mailto:ovsyannikov.ivan@gmail.com
*/

function the_tags_to_keywords($id = 0){
	echo get_the_tags_to_keywords($id);
	}

function get_the_tags_to_keywords($id = 0){
	global $wpdb;
	preg_match("!^".get_option('siteurl')."\/(.+?)=(\d+)$!is", get_the_guid(), $matches);
	if ($id == 0){
		if (is_array($matches)) $id = $matches[2];
		else return null;
		}
	if (is_single()){
		$keywords = '';
		$keywords .= "<meta name=\"keywords\" content=\"";
		$result = $wpdb->get_results("SELECT t.term_id tag_id, t.name tag_name, r.object_id FROM ".$wpdb->prefix."term_relationships r right join ".$wpdb->prefix."term_taxonomy tax using (term_taxonomy_id) left join ".$wpdb->prefix."terms t using (term_id) WHERE tax.taxonomy = 'post_tag' and r.object_id = '".$id."';", 'ARRAY_A');
		if (!empty($result)){
			$count = 0;
			foreach ($result as $tag){
				if ($count >= 1) $keywords .= ", ";
				$keywords .= $tag['tag_name'];
				$count++;
				}
			}
		$keywords .= "\" />\n";
		return $keywords;
		}
	else return null;
	}

add_action('wp_head', 'the_tags_to_keywords');
?>