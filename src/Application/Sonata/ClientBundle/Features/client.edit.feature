Feature: Client

  Scenario: Edit save client profile 1
    Given I logged in as "admin" with password "12345"
    When I go to "sonata/client/client/1/edit"
    And I press "Mettre à jour"
    Then I should see "Client1 - Général"

  Scenario: Create new client
    Given I logged in as "admin" with password "12345"
    When I go to "sonata/client/client/create"
    And I select "Prénom Nom" from "Gestionnaire"
    #And I fill field "nom" with "TEST Create client"
#    And I fill field "nom" with "TEST Create client"

#    And I press "Mettre à jour"
#    Then I should see "Client1 - Général"


