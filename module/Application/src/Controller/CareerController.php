<?php

declare(strict_types=1);

namespace Application\Controller;

use ContentMySqlExtDAO;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class CareerController extends AbstractActionController
{
    public function indexAction()
    {
        $careers = ContentController::getContent("type = 'career' ORDER BY display_order asc");
        $this->layout()->htmlClass = 'header-style-2';
        $this->layout()->header2 = true;
        return new ViewModel([
            'careers' => $careers,
        ]);
    }

    public function detailsAction()
    {
        $id = HelperController::filterInput($this->params('id'));
        $contentMySqlExtDAO = new ContentMySqlExtDAO();
        $careerInfo = $contentMySqlExtDAO->load($id);
        $this->layout()->htmlClass = 'header-style-2';
        $this->layout()->header2 = true;
        return new ViewModel([
            'careerInfo' => $careerInfo,
        ]);
    }

    public function careerSubmitAction()
    {
        $result = false;
        $msg = "Error";
        $firstName = HelperController::filterInput($this->getRequest()->getPost('first-name'));
        $lastName = HelperController::filterInput($this->getRequest()->getPost('last-name'));
        $email = HelperController::filterInput($this->getRequest()->getPost('email'));
        $mobile = HelperController::filterInput($this->getRequest()->getPost('mobile'));
        $message = HelperController::filterInput($this->getRequest()->getPost('cover-letter'));

        if ($firstName == "" || $lastName == "" || $email == "" || $mobile == "" || $message == "" || $_FILES['cv']['size'] == 0) {
            $msg = "Please fill all required inputs!";
        } else {
            if ($_FILES['cv']['tmp_name']) {
                $allowedExtensions = ['doc', 'docx', 'pdf'];
                $file = $_FILES['cv']['tmp_name'];
                $ext = pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION);
                //echo $ext;
                if (!in_array($ext, $allowedExtensions)) {
                    $result = false;
                    $msg = "Wrong file extension";
                } else {
                    $newName = $firstName . " " . $lastName . "_" . HelperController::random(5) . '.' . $ext;
                    $targetFile = BASE_PATH . upload_file_dir . $newName;
                    $upload = move_uploaded_file($file, $targetFile);
                    $textArray = [
                        '<b>Full Name: </b>' . $firstName . " " . $lastName,
                        '<b>Email: </b>' . $email,
                        '<b>Mobile: </b>' . $mobile,
                        '<b>CV: </b> <a download href="' . HelperController::getFileUrl($newName) . '">Download</a>',
                        '<b>CV Link: </b>' . HelperController::getFileUrl($newName),
                        '<b>Cover Letter: </b>' . $message,

                    ];
                    $emailBody = MailController::getEmailHtmlTemplate("New Application", $textArray);
                    //print_r($emailBody);
                    $to = OptionsController::getOption(OptionsController::$HR_EMAIL_ADDRESS);
                    $sendEmail = MailController::sendMail($to, 'New Applicant', $emailBody);
                    if ($sendEmail) {
                        $result = true;
                        $msg = "Your Application has been sent.";
                    }
                }
            }
        }
        $response = json_encode([
            'status' => $result,
            'msg' => $msg,
        ]);

        print_r($response);
        return $this->response;
    }
}
