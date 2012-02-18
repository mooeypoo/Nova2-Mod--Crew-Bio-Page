<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once MODPATH.'core/controllers/nova_personnel.php';

class Personnel extends Nova_personnel {

	public function __construct()
	{
		parent::__construct();
	}
	

	/**********************/
	/**** CREW BIO MOD ****/
	/**********************/
	public function character($id = false) {
		// load the models
		$this->load->model('ranks_model', 'ranks');
		$this->load->model('positions_model', 'pos');
		$this->load->model('posts_model', 'posts');
		$this->load->model('personallogs_model', 'logs');
		$this->load->model('news_model', 'news');
		$this->load->model('awards_model', 'awards');
		$this->load->helper('utility');
		
		// sanity check
		$id = (is_numeric($id)) ? $id : false;
		
		// grab the character info
		$character = $this->char->get_character($id);
		
		$data['postcount'] = 0;
		$data['logcount'] = 0;
		$data['newscount'] = 0;
		$data['awardcount'] = 0;
		
		if ($character !== false)
		{
			$data['postcount'] = $this->posts->count_character_posts($id);
			$data['logcount'] = $this->logs->count_character_logs($id);
			$data['newscount'] = $this->news->count_character_news($id);
			$data['awardcount'] = $this->awards->count_character_awards($id);
			
			$data['last_post'] = mdate($this->options['date_format'], gmt_to_local($character->last_post, $this->timezone, $this->dst));
			
			// set the name items into an array
			$name_array = array(
				'first_name' => $character->first_name,
				'middle_name' => $character->middle_name,
				'last_name' => $character->last_name,
				'suffix' => $character->suffix
			);
			
			// parse the name out
			$name = parse_name($name_array);
			$abbr_name = parse_name(array('first_name' => $character->first_name, 'last_name' => $character->last_name));
			
			// get the rank name
			$rank = $this->ranks->get_rank($character->rank, 'rank_name');
			
			// set the character info
			$data['character_info'] = array(
				array(
					'label' => ucfirst(lang('labels_name')),
					'value' => $name),
				array(
					'label' => ucfirst(lang('global_position')),
					'value' => $this->pos->get_position($character->position_1, 'pos_name')),
				array(
					'label' => ucwords(lang('order_second') .' '. lang('global_position')),
					'value' => $this->pos->get_position($character->position_2, 'pos_name')),
				array(
					'label' => ucfirst(lang('global_rank')),
					'value' => $rank),
			);

			/** CHAR AWARDS **/
			$allawards = $this->awards->get_awards_for_id($id,'character');
			$data['tempawards'] = $allawards;
			$datestring = $this->options['date_format'];

			if ($allawards->num_rows > 0) {
				$i = 1;
				foreach ($allawards->result() as $awrd) {
					$data['character_awards'][$i] = array(
						'id' => $awrd->award_id,
						'name' => $awrd->award_name,
						'date' => mdate($datestring, gmt_to_local($awrd->awardrec_date, $this->timezone, $this->dst)),
						'desc' => $awrd->award_desc,
						'reason' =>$awrd->awardrec_reason,
						'img' => array(
							'src' => base_url().Location::asset('images/awards', trim($awrd->award_image)),
							'alt' => $awrd->award_name,
							'class' => 'award_image',
						),
					); 
					$i++;
				}
			}
			
			/** CHAR AWARDS **/

			
			// set the data used by the view
			$data['character']['id'] = $id;
			$data['character']['name'] = $name;
			$data['character']['rank'] = $character->rank;
			$data['character']['position_1'] = $character->position_1;
			$data['character']['position_2'] = $character->position_2;
			$data['character']['user'] = $character->user;
			
			if ($character->images > '')
			{
				// get the images
				$images = explode(',', $character->images);
				$images_count = count($images);
				
				$src = (strstr($images[0], 'http://') !== false)
					? $images[0]
					: base_url().Location::asset('images/characters', trim($images[0]));
				
				// set the image
				$data['character']['image'] = array(
					'src' => $src,
					'alt' => $name,
					'class' => 'image',
					'width' => 200
				);
				
				// creating the empty array
				$data['character']['image_array'] = array();
				
				for ($i=1; $i < $images_count; $i++)
				{
					$src = (strstr($images[$i], 'http://') !== false)
						? trim($images[$i])
						: base_url().Location::asset('images/characters', trim($images[$i]));
					
					// build the array
					$data['character']['image_array'][] = array(
						'src' => $src,
						'alt' => $name,
						'class' => 'image'
					);
				}
			}
			else
			{
				// set the image
				$data['character']['noavatar'] = array(
					'src' => Location::img('no-avatar.png', $this->skin, 'main'),
					'alt' => '',
					'class' => 'image',
					'width' => 200
				);
			}
						
			// get the bio tabs
			$tabs = $this->char->get_bio_tabs();
			
			// get the bio sections
			$sections = $this->char->get_bio_sections();
			
			if ($tabs->num_rows() > 0)
			{
				$i = 1;
				foreach ($tabs->result() as $tab)
				{
					$data['tabs'][$i]['id'] = $tab->tab_id;
					$data['tabs'][$i]['name'] = $tab->tab_name;
					$data['tabs'][$i]['link'] = $tab->tab_link_id;
					
					++$i;
				}
			}
			
			if ($sections->num_rows() > 0)
			{
				$i = 1;
				foreach ($sections->result() as $sec)
				{
					$fields = $this->char->get_bio_fields($sec->section_id);
					
					if ($fields->num_rows() > 0)
					{
						$j = 1;
						foreach ($fields->result() as $field)
						{
							$data['fields'][$sec->section_id][$j]['label'] = $field->field_label_page;
							$data['fields'][$sec->section_id][$j]['value'] = false;
							
							$info = $this->char->get_field_data($field->field_id, $id);
							
							if ($info->num_rows() > 0)
							{
								foreach ($info->result() as $item)
								{
									$data['fields'][$sec->section_id][$j]['value'] = $item->data_value;
								}
							}
							
							++$j;
						}
					}
					
					if ($tabs->num_rows() > 0)
					{
						$data['sections'][$sec->section_tab][$i]['id'] = $sec->section_id;
						$data['sections'][$sec->section_tab][$i]['name'] = $sec->section_name;
					}
					else
					{
						$data['sections'][$i]['id'] = $sec->section_id;
						$data['sections'][$i]['name'] = $sec->section_name;
					}
					
					++$i;
				}
			}
			
			// set the header
			$data['header'] = $rank.' '.$abbr_name;
			
			$this->_regions['title'].= ucfirst(lang('labels_biography')).' - '.$abbr_name;
		}
		else
		{
			// set the header
			$data['header'] = sprintf(lang('error_title_invalid_char'), ucfirst(lang('global_character')));
			$data['msg_error'] = sprintf(lang_output('error_msg_invalid_char'), lang('global_character'));
			
			// set the title
			$this->_regions['title'].= lang('error_pagetitle');
		}
		
		if (Auth::is_logged_in())
		{
			$data['edit_valid_form'] = (Auth::check_access('site/bioform', false)) ? true : false;
			
			if (Auth::check_access('characters/bio', false) === true)
			{
				if (Auth::get_access_level('characters/bio') == 3)
				{
					$data['edit_valid'] = true;
				}
				elseif (Auth::get_access_level('characters/bio') == 2)
				{
					$characters = $this->char->get_user_characters($this->session->userdata('userid'), '', 'array');
					
					$data['edit_valid'] = (in_array($id, $characters) or $character->crew_type == 'npc')
						? true
						: false;
				}
				elseif (Auth::get_access_level('characters/bio') == 1)
				{
					$characters = $this->char->get_user_characters($this->session->userdata('userid'), '', 'array');
					
					$data['edit_valid'] = in_array($id, $characters);
				}
				else
				{
					$data['edit_valid'] = false;
				}
			}
			else
			{
				$data['edit_valid'] = false;
			}
		}
		else
		{
			$data['edit_valid'] = false;
			$data['edit_valid_form'] = false;
		}
		
		$data['label'] = array(
			'edit' => ucwords(lang('actions_edit').' '.lang('global_character')),
			'view_all_posts' => ucwords(lang('actions_seeall') .' '. lang('global_missionposts')),
			'view_all_logs' => ucwords(lang('actions_seeall') .' '. lang('global_personallogs')),
			'view_all_awards' => ucwords(lang('actions_seeall') .' '. lang('global_awards')),
			'view_all_images' => ucwords(lang('actions_seeall') .' '. lang('labels_images')),
			'view_user' => ucwords(lang('global_user').' '.lang('labels_info')),
			'mission_posts' => ucwords(lang('global_missionposts')),
			'personal_logs' => ucwords(lang('global_personallogs')),
			'news_items' => ucwords(lang('global_newsitems')),
			'comments' => ucwords(lang('labels_comments')),
			'last_post' => ucwords(lang('order_last').' '.lang('global_post')),
			'stats' => ucfirst(lang('labels_stats')),
			'back_manifest' => LARROW.' '.ucfirst(lang('actions_back')).' '.lang('labels_to').' '.ucfirst(lang('labels_manifest')),
		);
		
		$this->_regions['content'] = Location::view('personnel_character', $this->skin, 'main', $data);
		$this->_regions['javascript'] = Location::js('personnel_character_js', $this->skin, 'main');
		
		Template::assign($this->_regions);
		
		Template::render();
	}
	/**************************/
	/**** END CREW BIO MOD ****/
	/**************************/


	
}
