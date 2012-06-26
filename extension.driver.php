<?php

	Class extension_admin_rainbow_headline extends Extension{
	
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
                        Symphony::Configuration()->remove('admin_rainbow_headline');            
                        Administration::instance()->saveConfig();
                }


		public function initaliseAdminPageHead($context) {
			if(!extension_loaded('gd')) {
				Administration::instance()->Page->pageAlert(__('You don\'t have the GD library installed. Admin Rainbow Headline will remain disabled until you\'ve installed it.'), Alert::ERROR);
				return;
			}

			$page = Administration::instance()->Page;
			
			$color = General::Sanitize(Symphony::Configuration()->get('headline_color', 'admin_rainbow_headline'));
			
			if($color != "" && $color != "#") {
				$rgb = sscanf($color, '#%2x%2x%2x');
				$luminance = (0.2126*$rgb[0]) + (0.7152*$rgb[1]) + (0.0722*$rgb[2]);
				
				$style = "
						body #header h1 { background-color: " . $color . "; }";
				
				if($luminance > 125)
					$style .= "
						body #header a { color: #333333; text-shadow: -1px 2px 3px rgba(0,0,0,0.3); }
						body #header a:hover { color: #000000; }";

				$page->addElementToHead(new XMLElement("style", $style, array("type" => "text/css", "media" => "screen, projection")), 100012);
				
				$imagehandle = imagecreatetruecolor(16,16);
				$colorhandle = imagecolorallocate($imagehandle,$rgb[0],$rgb[1],$rgb[2]);
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
		
		public function savePreferences(){
			
			$headline_color = trim(strtoupper($context['settings']['admin_rainbow_headline']['headline_color']));
			if(strlen($headline_color) > 0 and substr($headline_color,0,1) != "#") {
				$headline_color = "#" . $headline_color;
			}
			
			Symphony::Configuration()->set('headline_color',substr(trim($headline_color), 0, 7),'admin_rainbow_headline');
		}

		public function appendPreferences($context){
			
			$color = General::Sanitize(Symphony::Configuration()->get('headline_color', 'admin_rainbow_headline'));
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
			$group->appendChild(new XMLElement('p', __('This can be any RGB-Hex value, for example <code>#97712B</code>.'), array('class' => 'help')));
						
			$div->appendChild($group);
			
			$context['wrapper']->appendChild($div);
						
		}
			
	}

?>
