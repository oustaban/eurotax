Feature: Client Admin - Garanties Create Error 5010

  Scenario: load page Garanties and create Garanties
    Given I logged in as "test" with password "test"
    When I go to "sonata/client/garantie/create?filter[client_id][value]=1"
    Then print last response

#    Then the response status code should be 200
    #When I should see "Modifier une garantie"
    #And I select "Garantie Bancaire" from "_type_garantie"
    #And I fill in "montant_montant" with "100"
    #And I fill in "nom_de_la_banques_id" with "100"
    #And I press "btn_create_and_edit"
    #Then I should see "Bonjour test"

