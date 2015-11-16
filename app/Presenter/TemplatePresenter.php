<?php
namespace App\Presenter;

use Laracasts\Presenter\Presenter;

class TemplatePresenter extends Presenter
{
	public function sectionMenu($template)
	{
		foreach ($template->section as $k => $v) {

            switch ($v) {

                case 'name':
                    $template->section['contact'] = 'Contact Information';
                    $template->section['contact']['name'] = '';
                    unset($template->section[$k]);
                    break;
                case 'address':
                    $template->section['contact'] = 'Contact Information';
                    $template->section['contact']['address'] = 'Address';
                    unset($template->section[$k]);
                    break;
                case 'photo':
                    $template->section['contact'] = 'Contact Information';
                    $template->section['contact']['photo'] = 'Photos';
                    unset($template->section[$k]);
                    break;
                case 'email':
                    $template->section['contact'] = 'Contact Information';
                    $template->section['contact']['email'] = 'Email Address';
                    unset($template->section[$k]);
                    break;
                case 'profile_website':
                    $template->section['contact'] = 'Contact Information';
                    $template->section['contact']['profile_website'] = 'My Profile Website';
                    unset($template->section[$k]);
                    break;
                case 'linkedin':
                    $template->section['contact'] = 'Contact Information';
                    $template->section['contact']['linkedin'] = 'My LinkedIn Profile';
                    unset($template->section[$k]);
                    break;
                case 'phone':
                    $template->section['contact'] = 'Contact Information';
                    $template->section['contact']['phone'] = 'Phone Number';
                    unset($template->section[$k]);
                    break;
                default:
                    $template->section['contact'] = 'Contact Information';
                    break;
            }
        }

        return $template;
	}
}