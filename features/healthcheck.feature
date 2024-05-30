Feature: Get a 200 and OK-status if container is ready

  Scenario: Get a 200 and OK-status if container is ready
    When I send a "GET" request to "/healthcheck"
    Then response status code should be 200
    Then the response should be a JSON object containing:
      | status | OK |
