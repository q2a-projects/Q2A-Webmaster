<?php
class qa_webmaster_admin {
	
	var $directory;
	var $urltoroot;
	
	function load_module($directory, $urltoroot)
	{
		$this->directory=$directory;
		$this->urltoroot=$urltoroot;
	}

	
	function option_default($option)
	{
		switch ($option) {
			case 'wm_link_nav':
				return true;
				break;
		}
	}


	function admin_form()
	{
		$saved=false;
		
		if (qa_clicked('wm_save_button')) {
			qa_opt('wm_link_nav', (int)qa_post_text('wm_link_nav'));
			
			$saved=true;
		}
		
		$form=array(
			'ok' => $saved ? 'settings saved' : null,
			
			'fields' => array(
				array(
					'label' => 'Show Webmaster Link in header navigation',
					'value' => qa_html(qa_opt('wm_link_nav')),
					'tags' => 'name="wm_link_nav"',
					'type' => 'checkbox',
				),
				array(
					'type' => 'static',
					'value' =>'<hr>',
				),
				array(
					'type' => 'static',
					'value' =>'Visit <a href="'. qa_opt('site_url') .'index.php?qa=webmaster">Webmaster Tool</a>',
				),
			),
			
			'buttons' => array(
				array(
					'label' => 'Save Changes',
					'tags' => 'name="wm_save_button"',
				),
			),			);

		return $form;
	}	
}


/*
	Omit PHP closing tag to help avoid accidental output
*/