Feature: Message
  I need to be able to send messages

  Scenario: I create a message
    When I create a message from "sender" to "recipient"
    Then the message should be created
    And the message status should be "created"

  Scenario: I send a message
    When I send a message from "sender" to "recipient"
    Then the message should be created
    And the message should be sent
    And the message status should be "sent"

  Scenario: I read a message
    When I read a message from "sender" to "recipient"
    Then the message should be created
    And the message should be sent
    And the message should be read
    And the message status should be "read"

  Scenario: I delete a message
    When I delete a message from "sender" to "recipient"
    Then the message should be created
    And the message should be sent
    And the message should be archived
    And the message status should be "archived"