<?php

declare(strict_types=1);

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class ContactController extends AbstractActionController
{
    public function indexAction()
    {
        $bannerLocation = 3;
        $banners = ContentController::getBanners($bannerLocation);
        return new ViewModel([
            'banner' => $banners[0],
        ]);
    }

    public function contactSubmitAction()
    {
        $result = false;
        $msg = "Error";
        //$fullName = HelperController::filterInput($this->getRequest()->getPost('full-name'));
        $firstName = HelperController::filterInput($this->getRequest()->getPost('first-name'));
        $lastName = HelperController::filterInput($this->getRequest()->getPost('last-name'));
        $email = HelperController::filterInput($this->getRequest()->getPost('email'));
        $mobile = HelperController::filterInput($this->getRequest()->getPost('mobile'));
        $message = HelperController::filterInput($this->getRequest()->getPost('message'));

        if ($firstName == "" || $lastName == "" || $email == "" || $mobile == "" || $message == "") {
            $msg = "Please fill all required inputs!";
        } else {
            $result = true;
            $msg = "Your Message has been sent.";
        }
        $response = json_encode([
            'status' => $result,
            'msg' => $msg,
        ]);

        print_r($response);
        return $this->response;
    }
}
