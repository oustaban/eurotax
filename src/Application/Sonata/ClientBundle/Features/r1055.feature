Feature: Client Admin - Garanties Create Error 500

  Scenario: load page Garanties and create Garanties
    Given I logged in as "admin" with password "12345"
    When I go to "/sonata/client/garantie/create?filter[client_id][value]=1"
    And I select "Garantie Bancaire" from "Type"
    And I fill field "montant_montant" with "250"
    And I press "Créer"
    Then I should see "Client1 - Garanties"