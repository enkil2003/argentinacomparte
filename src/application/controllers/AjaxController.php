<?php
class AjaxController extends Zend_Controller_Action
{
    const DEBUG = true;
    
    public function init() {
        $request = $this->getRequest();
        if (!self::DEBUG) {
            if (!$request->isXmlHttpRequest()) {
                throw new Exception('only ajax request here');
            }
        }
        header('content-type: application/json');
    }
    public function pollAction() {
        $request = $this->getRequest();
        $id = $request->getPost('id');
        $poll = Doctrine_Core::getTable('Poll')->find($id);
        if ($request->getCookie("survey_{$id}") == true) {
            echo json_encode(
                array(
                    'status' => 'alreadyVoted',
                    'options' => array(
                        'option1' => (int)$poll->optiononevotes,
                        'option2' => (int)$poll->optiontwovotes,
                        'option3' => (int)$poll->optionthreevotes,
                        'option4' => (int)$poll->optionfourvotes
                    )
                )
            );
            die;
        }
        setCookie("survey_{$id}", true, time() + 60*60*24, '/');
        $option = $request->getPost('option');
        switch($option) {
            case 'option1':
                $field = 'optiononevotes';
                break;
            case 'option2':
                $field = 'optiontwovotes';
                break;
            case 'option3':
                $field = 'optionthreevotes';
                break;
            case 'option4':
                $field = 'optionfourvotes';
                break;
        }
        $poll->{$field} += 1;
        try {
            $poll->save();
            echo json_encode(
                array(
                    'status' => 'success',
                    'options' => array(
                        'option1' => (int)$poll->optiononevotes,
                        'option2' => (int)$poll->optiontwovotes,
                        'option3' => (int)$poll->optionthreevotes,
                        'option4' => (int)$poll->optionfourvotes
                    )
                )
            );
        } catch (Exception $e) {
            echo json_encode(
                array(
                    'status' => 'error',
                    'message' => 'No se pudo guardar en la db'
                )
            );
        }
        die;
    }
}