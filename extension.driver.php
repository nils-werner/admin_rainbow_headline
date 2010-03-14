<?php

	Class extension_admin_rainbow_headline extends Extension{
	
		public function about(){
			return array('name' => 'Admin Rainbow Headline',
						 'version' => '1.4',
						 'release-date' => '2009-10-25',
						 'author' => array('name' => 'Nils Werner',
										   'website' => 'http://www.phoque.com/projekte/symphony',
										   'email' => 'nils.werner@gmail.com')
				 		);
		}
		
		
		public function getSubscribedDelegates() {
			return array(
				array(
					'page'		=> '/backend/',
					'delegate'	=> 'InitaliseAdminPageHead',
					'callback'	=> 'initaliseAdminPageHead'
				),
						
				array(
					'page' => '/system/preferences/',
					'delegate' => 'AddCustomPreferenceFieldsets',
					'callback' => 'appendPreferences'
				),	
				
				array(

					'page' => '/system/preferences/',
					'delegate' => 'Save',
					'callback' => 'savePreferences'

				),
			);
		}
                
                public function uninstall(){
                        Administration::instance()->Configuration->remove('admin_rainbow_headline');            
                        Administration::instance()->saveConfig();
                }


		public function initaliseAdminPageHead($context) {
			$page = $context['parent']->Page;
			
			$color = General::Sanitize(Administration::instance()->Configuration->get('headline_color', 'admin_rainbow_headline'));
			
			if($color != "") {
				$page->addElementToHead(new XMLElement("style", "body form h1, body form ul#usr { background-color: " . $color . "; }", array("type" => "text/css", "media" => "screen, projection")), 100012);
				
				$imagehandle = imagecreatetruecolor(16,16);
				$colorarray = sscanf($color, '#%2x%2x%2x');
				$colorhandle = imagecolorallocate($imagehandle,$colorarray[0],$colorarray[1],$colorarray[2]);
				imagefill($imagehandle,0,0,$colorhandle);
				ob_start();
				imagepng($imagehandle);
				$ico64data = base64_encode(ob_get_contents());
				ob_end_clean();
				
				$page->addElementToHead(new XMLElement("link", NULL, array("rel" => "shortcut icon", "href" => "data:image/png;base64," . $ico64data, "type" => "image/png")), 100013);
				
				unset($colorhandle);
				unset($ico64data);
				imagedestroy($imagehandle);
			}
		}
		
		public function savePreferences($context){
			
			$headline_color = trim(strtoupper($context['settings']['admin_rainbow_headline']['headline_color']));
			if(strlen($headline_color) > 0 and substr($headline_color,0,1) != "#") {
				$headline_color = "#" . $headline_color;
			}
			
			$context['settings']['admin_rainbow_headline'] = array("headline_color" => substr(trim($headline_color), 0, 7));
		}

		public function appendPreferences($context){
			
			$color = General::Sanitize(Administration::instance()->Configuration->get('headline_color', 'admin_rainbow_headline'));
			if($color=="")
				$color="#";
			
			$div = new XMLElement('div');
			$div->setAttribute('class', 'field field-colorchooser');
			
			$group = new XMLElement('fieldset');
			$group->setAttribute('class', 'settings');
			$group->appendChild(new XMLElement('legend', __('Admin Rainbow Headline')));


			$label = Widget::Label(__('Headline Background Color'));
			// TODO: # per default anzeigen
			$label->appendChild(Widget::Input('settings[admin_rainbow_headline][headline_color]', $color));		
			$label->setAttribute('class', 'color-chooser');
			$group->appendChild($label);
			$group->appendChild(new XMLElement('p', __('This can be any RGB-Hexvalue, for example <code>#97712B</code>'), array('class' => 'help')));
						
			$div->appendChild($group);
			
			$context['wrapper']->appendChild($div);
						
		}
			
	}

?>