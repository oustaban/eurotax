Feature: Client Admin - Garanties Create Error 500

  Scenario: load page Garanties and create Garanties
    Given I logged in as "admin" with password "12345"
    When I go to "/sonata/client/garantie/create?filter[client_id][value]=1"
    And I select "Garantie Bancaire" from "Type"
    And I fill field "Montant *" with "100"
    And I fill in "Émetteur" with "100"
    And I press "Créer"
    Then I should see "Ajouter / Modifier une garantie"