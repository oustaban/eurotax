Feature: ApplicationSonataClientBundle

Scenario: Open main page in dev
Given I have done something with "/login"
When Status "200"
Then I should get "TEST"
