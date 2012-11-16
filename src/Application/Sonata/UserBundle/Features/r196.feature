Feature:

  Scenario: Open main page in dev
    Given I logged in as "test" with password "test"
    Then I should see "Bonjour test"
