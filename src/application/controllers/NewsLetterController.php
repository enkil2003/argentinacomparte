<?php
/**
 * News Letter Controller
 * @author pablo
 *
 */

class NewsLetterController extends Zend_Controller_Action
{
    public function inscripcionAction()
    {
        $layout = Zend_Layout::getMvcInstance()->setLayout('blank');
        $this->getHelper('viewRenderer')->setNoRender();
        $request = $this->getRequest();
        $form = new Application_Form_NewsLetter();
        if ($request->isPost() && $form->isValid($request->getPost())) {
            //do something
        }
        else {echo $form;}
        
    }
}
