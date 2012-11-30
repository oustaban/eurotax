Feature: V01-TVA

  Scenario: create V01-TVA
    Given I logged in as "admin" with password "12345"
    When I go to "/sonata/clientoperations/v01tva/list?filter[client_id][value]=1&month=11|2012"
    And I press "Ajouter une ligne" button
    And I fill field "tiers" with "Tiers"
    And I fill field "date_piece" with "27/11/2012"
    And I fill field "numero_piece" with "111111"
    And I select "USD" from "Devise *"
    And I fill field "montant_HT_en_devise" with "500,00"
    And I fill field "taux_de_TVA" with "1,999"
    And I select "USD" from "Paiement Devise *"
    And I fill field "paiement_montant" with "100,00"
    And I fill field "paiement_date" with "27/11/2012"
    And I fill field "taux_de_change" with "1,00000"
    And I fill field "HT" with "500,00"
    And I fill field "TVA" with "1,00"
    And I fill field "commentaires" with "Commentaires"
    And I press "ajax-btn_create" button
    Then I should not see "L'élément a été mis à jour avec succès."