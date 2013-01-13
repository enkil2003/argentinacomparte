<?php 

class UserController extends Zend_Controller_Action
{
    /**
     * Default view for logout action.
     * @return void
     */
    public function logoutAction()
    {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            Zend_Auth::getInstance()->clearIdentity();
        }
        $this->_redirect('/');
    }
    
    /**
     * Handles login form and login process
     * @return void
     */
    public function loginAction()
    {
        // avoid relogin
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $this->_helper->redirector('user-already-logged-in');
        }
        
        $request = $this->getRequest();
        // configure the form
        $form = new Form_Login();
        
        // receive the form
        if ($request->isPost() && $form->isValid($request->getPost())) {
            $adapter = new My_Auth_Adapter($form->getValue('usernameOrEmail'), $form->getValue('password'));
            /*
             * @FIX: tuve que poner esta linea abajo, sino no autenticaba el servidor, pero localmente no lo necesitaba
             * llegue por casualidad a esta linea mirando como autentica el zend_auth::authenticate
             */
            $result = $adapter->authenticate();
            $auth->authenticate($adapter);
            if ($auth->hasIdentity()) {
                $this->_helper->_redirector->gotoSimpleAndExit(
                    $this->view->translate('/admin'),
                    ''
                );
            } else {
//                $form->markAsError();
//                $this->view->errors = array_shift($result->getMessages());
            }
        } else {
            $this->view->error = $this->view->translate('El usuario/email y password no coinciden con ningun usuario registrado');
        }
        $this->view->form = $form;
    }
    
    /**
     * Default view password reset action.
     * @return void
     */
    public function passwordResetAction()
    {
        $request = $this->getRequest();
        $form = new Form_PasswordReset();
        if ($request->isPost() && $form->isValid($request->getPost())) {
            // envio un mail al usuario con un vinculo y un hash unico
            $config = Zend_Registry::get('config')->email->noreply;
            $resetCode = uniqid('reset');
            $mail = Vassilymas_Service_Locator::createEmailObject();
            try {
                User::setResetCode($form->getValue('email'), $resetCode);
                $fullname = User::getUserFullNameByEmail($form->getValue('email'));
                
                $mail->send(
                    Zend_Registry::get('config')->email->noreply,
                    'Vassilymas',
                    $form->getValue('email'),
                    '',
                    $this->view->translate('Resetear contraseña'),
                    'password-reset',
                    array(
                        'fullname' => $fullname,
                        'url' => "http://{$_SERVER['HTTP_HOST']}/" . $this->view->translate('cambiar-contraseña') .
                            "?code={$resetCode}"
                    )
                );
                /* @TODO cambiar esto a redirect para prevenir refresh del form */
                $this->render('password-reset-mail-sent');
            } catch (Exception $e) {
                echo $e->getMessage();
                die;
            } 
            return;
        }
        $form->addDefaultSubmit($this->view->translate('Recuperar contraseña'));
        $this->view->form = $form;
    }
    
    /**
     * Default view for reset password confirmation action.
     * @return void
     */
    public function resetPasswordConfirmationAction()
    {
        $request = $this->getRequest();
        
        $user = User::getUserByResetCode($request->getParam('code'));
        $fullname = Userdata::getFullNameByUserId($user['id']);
        $password = User::confirmResetCode($request->getParam('code'));
        $mail = Vassilymas_Service_Locator::createEmailObject();
        $mail->send(
            Zend_Registry::get('config')->email->noreply,
            'Vassilymas',
            $user['email'],
            '',
            $this->view->translate('Su clave ha cambiado'),
            'reset-password-confirmation',
            array(
                'fullname' => $fullname,
                'password' => $password,
                'username' => $user['username']
            )
        );
        
        $this->_helper->loadDefaultCss();
        $this->view->password = $password;
    }
}
