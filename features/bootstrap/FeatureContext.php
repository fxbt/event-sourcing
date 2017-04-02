<?php

use AppBundle\Event\EventInterface;
use AppBundle\Event\MessageCreated;
use AppBundle\Event\MessageSent;
use AppBundle\Event\MessageRead;
use AppBundle\Event\MessageArchived;
use AppBundle\Entity\Message;
use Behat\Behat\Context\Context;
use Behat\Mink\Exception\UnsupportedDriverActionException;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Behat\Hook\Scope\AfterStepScope;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context
{
    /**
     * @var Message
     */
    private $message;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @When /^I create a message from "([^"]*)" to "([^"]*)"$/
     */
    public function iCreateAMessageFromTo($sender, $recipient)
    {
        $this->message = Message::create(1, $sender, $recipient);
    }

    /**
     * @When /^I send a message from "([^"]*)" to "([^"]*)"$/
     */
    public function iSendAMessageFromTo($sender, $recipient)
    {
        $this->message = Message::create(1, $sender, $recipient);
        $this->message->send(1);
    }

    /**
     * @When /^I read a message from "([^"]*)" to "([^"]*)"$/
     */
    public function iReadAMessageFromTo($sender, $recipient)
    {
        $this->message = Message::create(1, $sender, $recipient);
        $this->message->send(1);
        $this->message->read(1);
    }

    /**
     * @When /^I delete a message from "([^"]*)" to "([^"]*)"$/
     */
    public function iDeleteAMessageFromTo($sender, $recipient)
    {
        $this->message = Message::create(1, $sender, $recipient);
        $this->message->send(1);
        $this->message->archive(1);
    }

    /**
     * @Then /^the message should be created$/
     */
    public function aMessageShouldBeCreated()
    {
        $events = $this->message->getEvents();

        $matchingEvents = array_filter($events, function(EventInterface $event) {
            return $event instanceof MessageCreated;
        });

        if (count($matchingEvents) !== 1) {
            throw new \RuntimeException('No message created');
        }
    }

    /**
     * @Then /^the message should be sent$/
     */
    public function aMessageShouldBeSent()
    {
        $events = $this->message->getEvents();

        $matchingEvents = array_filter($events, function(EventInterface $event) {
            return $event instanceof MessageSent;
        });

        if (count($matchingEvents) !== 1) {
            throw new \RuntimeException('No message sent');
        }
    }

    /**
     * @Then /^the message should be read$/
     */
    public function aMessageShouldBeRead()
    {
        $events = $this->message->getEvents();

        $matchingEvents = array_filter($events, function(EventInterface $event) {
            return $event instanceof MessageRead;
        });

        if (count($matchingEvents) !== 1) {
            throw new \RuntimeException('No message read');
        }
    }

    /**
     * @Then /^the message should be archived/
     */
    public function aMessageShouldBeArchived()
    {
        $events = $this->message->getEvents();

        $matchingEvents = array_filter($events, function(EventInterface $event) {
            return $event instanceof MessageArchived;
        });

        if (count($matchingEvents) !== 1) {
            throw new \RuntimeException('No message archived');
        }
    }

    /**
     * @Then /^the message status should be "([^"]*)"$/
     */
    public function aMessageStatusShouldBe($status)
    {
        if ($this->message->getStatus() !== $status) {
            throw new \RuntimeException(sprintf('Wrong status for message ("%s", should be "%s")', $this->message->getStatus(), $status));
        }
    }

    /**
     * @AfterStep
     */
    public function getInfoAfterStepFailure(AfterStepScope $scope)
    {
        if (99 === $scope->getTestResult()->getResultCode()) {
            $fileName = __DIR__.'/../../var/logs/behat/'
                .date('YmdHis').'_'
                .basename($scope->getFeature()->getFile())
                .'_line-'.$scope->getStep()->getLine();

            try {
                $content = $this->getSession()->getDriver()->getContent();

                file_put_contents($fileName.'.html', $content);
            } catch (UnsupportedDriverActionException $e) {
                // Do nothing
            }

            try {
                $screenshot = $this->getSession()->getDriver()->getScreenshot();

                file_put_contents($fileName.'.png', $screenshot);
            } catch (UnsupportedDriverActionException $e) {
                // Do nothing
            }
        }
    }
}
