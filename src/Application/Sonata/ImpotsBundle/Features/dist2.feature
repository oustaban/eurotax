Feature: tax

  Scenario: tax
    Given I logged in as "admin" with password "1111" drupal
    And I save list drupal
    Then the response status code should be 200
