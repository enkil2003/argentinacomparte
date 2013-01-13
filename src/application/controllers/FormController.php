<?php
/**
 * Index Controller
 * @author ricardo
 *
 */
class FormController extends Zend_Controller_Action
{
    
    public function init()
    {

    }
    
    public function guestbookAction()
    {
        $controllerFront = Zend_Controller_Front::getInstance();
        $returnUrl = $controllerFront->getRequest()->getScheme() . '://';
        $returnUrl .= $controllerFront->getRequest()->getHttpHost();
        $returnUrl .= $controllerFront->getRequest()->getRequestUri();
        
        $form = new Application_Form_Guestbook();
        $request = $this->getRequest();
        if ( $request->getPost('asunto') == 'guestbook' ) 
        {
            $email = $request->getPost('email');
            $message = $request->getPost('message');
            $config = array( 'port' => 25, 'username' => 'noreply@argentinacomparte.gob.ar');
            $transport = new Zend_Mail_Transport_Smtp('smtp.sgp.gov.ar', $config);
            Zend_Mail::setDefaultTransport($transport);
            
            $mail = new Zend_Mail('utf-8');
            $mail->setBodyText( $message );
            $mail->setFrom( 'noreply@argentinacomparte.gob.ar','Mensajero ArgentinaComparte' );
            $mail->setSubject ( 'Comentario desde la web' );
            $mail->addTo('argentinacomparte1@gmail.com', 'Argentina Comparte');
            $mail->setBodyHtml( $email .'<br/>Escribió:<br/>'. $message . 
                    '<br/><br/>Se envió desde la siguiente página: <a href="' . $returnUrl . '">' .
                    $returnUrl . '</a>');
            
            $sent = true;
            try {
                  $mail->send();
            } catch (Exception $e) {
                  $sent = false;
            }
            
            if($sent){
                $this->view->guestbook = '<br/><h3>* Gracias por tu mensaje.</h3><br/>' ;
            } else {
                $this->view->guestbook = '<br/><h3>* No se pudo enviar el mensaje.</h3><p>'.$e->getMessage().'</p><br/>';
            }
        }else{
            $this->view->guestbook = $form;
        } 
    }
    public function newsletterAction()
    {
        $layout = Zend_Layout::getMvcInstance()->setLayout('blank');
        $request = $this->getRequest();
        $form = new Application_Form_NewsLetter();
        if ($request->isPost() && $form->isValid($request->getPost())) {
            $email = $request->getPost('email');
            $name = $request->getPost('name');
            $message = "Nueva suscripción al newsletter: ".$name." <".$email.">;" ;

            $config = array( 'port' => 25, 'username' => 'noreply@argentinacomparte.gob.ar');
            $transport = new Zend_Mail_Transport_Smtp('smtp.sgp.gov.ar', $config);
            Zend_Mail::setDefaultTransport($transport);
            
            $mail = new Zend_Mail('utf-8');
            $mail->setBodyText( $message );
            $mail->setFrom( 'noreply@argentinacomparte.gob.ar','noreply ArgentinaComparte' );
            $mail->setSubject ( 'Solicitud de inscripción al NewsLetter de ArgentinaComparte' );
            $mail->addTo('argentinacomparte1@gmail.com', 'Argentina Comparte');
            $mail->setBodyHtml( 'Nueva inscripción al Newsletter <br/><br/>'.$name.'<br/>'. $email );
            
            $sent = true;
            try {
            	$mail->send();
            } catch (Exception $e) {
            	$sent = false;
            }
            
            if($sent){
            	$this->view->newsletter = '<br/><h2>* Suscripción recibida.</h2><br/>' ;
            } else {
            	$this->view->newsletter = '<br/><h2>* No se pudo enviar la inscripción.</h2><p>'.$e->getMessage().'</p><br/>';
            }
        }
        else { $this->view->newsletter = $form;}
    
    }
}
