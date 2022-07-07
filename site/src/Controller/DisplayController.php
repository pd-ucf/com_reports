<?php
namespace PD\Component\Reports\Site\Controller;
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

class DisplayController extends BaseController {
    
    public function display($cachable = false, $urlparams = array()) {        
        $document = Factory::getDocument();
        $document->setTitle('Reports');
        $app = Factory::getApplication();
        $menu = $app->getMenu();
        $items = $menu->getMenu();
        foreach ($items as $item) {
            if($item->title == 'Reports') {
                $menu->setActive($item->id);
            }
        }
        $menu->setActive(103);
        $viewName = $this->input->getCmd('view', 'login');
        $viewFormat = $document->getType();
        $view = $this->getView($viewName, $viewFormat);
        $view->document = $document;
        $view->display();
    }
    
}
