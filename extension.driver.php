<?php

	Class extension_admin_rainbow_headline extends Extension{
	
		public function about(){
			return array('name' => 'Admin Rainbow Headline',
						 'version' => '1.1',
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
				
				include_once(EXTENSIONS . '/admin_rainbow_headline/lib/imagebmp.php');
				
				$imagehandle = imagecreatetruecolor(16,16);
				$colorarray = sscanf($color, '#%2x%2x%2x');
				$colorhandle = imagecolorallocate($imagehandle,$colorarray[0],$colorarray[1],$colorarray[2]);
				imagefill($imagehandle,0,0,$colorhandle);
				ob_start();
				imagebmp($imagehandle);
				$ico64data = base64_encode(ob_get_contents());
				ob_end_clean();
				
				
				$page->addElementToHead(new XMLElement("link", NULL, array("rel" => "shortcut icon", "href" => "data:image/x-icon;base64," . $ico64data, "type" => "image/x-icon")), 100013);
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
			
			$group = new XMLElement('fieldset');
			$group->setAttribute('class', 'settings');
			$group->appendChild(new XMLElement('legend', 'Admin Rainbow Headline'));


			$label = Widget::Label('Headline Background Color');
			$label->appendChild(Widget::Input('settings[admin_rainbow_headline][headline_color]', General::Sanitize(Administration::instance()->Configuration->get('headline_color', 'admin_rainbow_headline'))));		
			$group->appendChild($label);
			$group->appendChild(new XMLElement('p', 'This can be any RGB-Hexvalue, for example <code>#97712B</code>', array('class' => 'help')));
						
			$context['wrapper']->appendChild($group);
						
		}
			
	}

?>