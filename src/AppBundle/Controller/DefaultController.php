<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Message;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        $messages = $this->get('message_repository')->findAll();

        return $this->render(':default:index.html.twig', [
            'messages' => $messages
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function createAction(Request $request)
    {
        $sender = $request->get('sender');
        $recipient = $request->get('recipient');

        $message = Message::create(uniqid('', true), $sender, $recipient);

        $this->get('message_repository')->save($message);

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/send/{uuid}", name="send")
     */
    public function sendAction($uuid)
    {
        try {
            $message = $this->get('message_repository')->find($uuid);
            $message->send($uuid);

            $this->get('message_repository')->save($message);
            $this->addFlash('success', sprintf("Message %s sent", $uuid));
        } catch (\Exception $e) {
            $this->addFlash('danger', sprintf("Can't send message %s", $uuid));
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/read/{uuid}", name="read")
     */
    public function readAction($uuid)
    {
        try {
            $message = $this->get('message_repository')->find($uuid);
            $message->read($uuid);

            $this->get('message_repository')->save($message);
            $this->addFlash('success', sprintf("Message %s read", $uuid));
        } catch (\Exception $e) {
            $this->addFlash('danger', sprintf("Can't read message %s", $uuid));
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/archive/{uuid}", name="archive")
     */
    public function archiveAction($uuid)
    {
        try {
            $message = $this->get('message_repository')->find($uuid);
            $message->archive($uuid);

            $this->get('message_repository')->save($message);
            $this->addFlash('success', sprintf("Message %s archived", $uuid));
        } catch (\Exception $e) {
            $this->addFlash('danger', sprintf("Can't archive message %s", $uuid));
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/delete/{uuid}", name="delete")
     */
    public function deleteAction($uuid)
    {
        try {
            $message = $this->get('message_repository')->find($uuid);

            $this->get('message_repository')->delete($message);
            $this->addFlash('success', sprintf("Message %s deleted", $uuid));
        } catch (\Exception $e) {
            $this->addFlash('danger', sprintf("Can't delete message %s", $uuid));
        }

        return $this->redirectToRoute('home');
    }
}
