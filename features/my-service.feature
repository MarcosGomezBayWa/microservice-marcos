@example
Feature: Feature

  Scenario: Get the list of ...
    Given I am authenticated as a user "pascal.paulis.testing"
    When I send a "GET" request to "/my-service"
    Then echo last response
