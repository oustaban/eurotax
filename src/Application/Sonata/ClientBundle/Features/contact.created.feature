Feature: Client Admin - Garanties Create Error 500

  Scenario: load page Garanties and create Garanties
    Given I logged in as "admin" with password "12345"
    When I go to "/sonata/client/contact/create?filter[client_id][value]=1"
    And I select "Mr." from "Civilité"
    And I fill field "nom" with "Nom 1"
    And I fill field "prenom" with "Prenom 1"
    And I fill field "telephone_1" with "111-111-111"
    And I fill field "email" with "test1@eurotax.fr"
    And I fill field "raison_sociale_societe" with "11111111"
    And I select "1" from "Affichage Facture"
    And I press "Créer"
    Then I should see "Client1 - Contacts"