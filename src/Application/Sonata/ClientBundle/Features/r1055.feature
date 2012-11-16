Feature: Client Admin - Garanties Create Error 500

  Scenario: load page Garanties and create Garanties
    Given I logged in as "admin" with password "12345"
    When I go to "/sonata/client/garantie/create?filter[client_id][value]=1"
    And I select "Garantie Bancaire" from "Type"
    And I fill in "Montant *" with "100"
    And I fill in "Émetteur" with "100"
    #And I fill in "nom_de_la_banques_id" with "100"
    And I press "Créer"
#    Then I should see "Bonjour Prénom Nom"
#    Then I should see "Utilisateurs"
#    Then print last response
    Then I should see "Modifier une garantie"

