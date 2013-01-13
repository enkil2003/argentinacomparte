<?php
/**
 * Index Controller
 * @author ricardo
 *
 */
class IndexController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $facebook = Zend_Registry::get('Facebook');
        $user = $facebook->getUser();
        if ($user) {
            try {
                // Proceed knowing you have a logged in user who's authenticated.
                $user_profile = $facebook->api('/me');
            } catch (Facebook_Api_Exception $e) {
                $user = null;
            }
        }
        // Login or logout url will be needed depending on current user state.
        if ($user) {
            $logoutUrl = $facebook->getLogoutUrl();
            $this->view->link = $logoutUrl;
            $this->view->user = $user_profile;
        } else {
            $loginUrl = $facebook->getLoginUrl(array('scope' => 'email,publish_stream'));
            $this->view->link = $loginUrl;
        }
    }
}
