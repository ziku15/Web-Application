<?php

namespace Biz_mela\Frontend\Controllers;

use Phalcon\Tag as Tag,
    Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\TextArea,
    Phalcon\Validation\Validator\PresenceOf;

class ContactController extends ControllerBase
{

	public function initialize()
    {
		//$this->view->setTemplateAfter('myaccount');
		
        
        
    }
	
	public function indexAction()
    {
    
    }

    public function contactAction()
    {
    	$form = new Form();

    	$email = new Text("email", array(
            'class' => 'form-control input-lg form-element',
            'id' => 'email',
            'placeholder' => 'Email Address',
            'autocomplete' => 'on'
        ));
		$email->addValidator(new PresenceOf(array(
            'message' => 'The email field is required'
        )));

        $comments = new TextArea("comments", array(
            'class' => 'form-control input-lg form-element',
            'id' => 'comments',
            'rows' => '5',
            'placeholder' => 'Your Comments',
            'autocomplete' => 'off'
        ));

        $comments->addValidator(new PresenceOf(array(
            'message' => 'The Comment field is required'
        )));

        $form->add($email);
        $form->add($comments);

        $data['form'] = $form;
        $this->view->setVars($data);

        if ($this->request->isPost()) {
        	if (!$form->isValid($_POST)) {
                $this->flash->error("Please solve the following errors !!");
            } else {
            	$email = $this->request->getPost('email');
            	$comments = $this->request->getPost('comments');

                $to="shibly_buet@yahoo.com";
                        
                $subject="Customer Comments";
                $body=$comments ;
                $headers="$email";

                //mail($to,$subject,$body,$headers);
                //$this->Send_Mail($to,$subject,$body);

                return $this->dispatcher->forward(
                        array(
                            'controller' => 'accdashboard',
                            'action' => 'dashinfo'
                            
                            
                            )
                        );


            	


            }
        }


    }


    private function Send_Mail($to,$subject,$body,$headers)
    {
    //echo __DIR__; exit();
    // require_once __DIR__ . '/../../../PHPMailer/class.phpmailer.php';
    //$from       = "info@optimaxbd.net";
    $from       = $headers;
    $mail       = new PHPMailer();
    $mail->IsSMTP(true);            // use SMTP
    $mail->IsHTML(true);
    $mail->SMTPAuth   = true;                  // enable SMTP authentication
    $mail->Host       = "smtp.optimaxbd.net"; // SMTP host
    $mail->Port       =  25;                    // set the SMTP port
    $mail->Username   = "";  // SMTP  username
    $mail->Password   = "";  // SMTP password
    $mail->SetFrom($from, '$headers');
    $mail->AddReplyTo($from,'$headers');
    $mail->Subject    = $subject;
    $mail->MsgHTML($body);
    $address = $to;
    $mail->AddAddress($address, $to);
    $mail->Send();
    if(!$mail->Send()) {
        $this->flash->error("Mail not Sent!! Mailer error:". $mail->ErrorInfo);
        
        } else {
        $this->flash->success("Mail Sent!!");
        return $this->response->redirect('contact/contact/');
        }
    }



















}