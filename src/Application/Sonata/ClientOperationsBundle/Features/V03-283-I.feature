Feature: V03-283-I

  Scenario: create V03-283-I
    Given I logged in as "admin" with password "12345"
    When I go to "/sonata/clientoperations/v03283i/list?filter[client_id][value]=1&month=11|2012"
    And I press "Ajouter une ligne" button
    And I fill field "tiers" with "Tiers"
    And I fill field "no_TVA_tiers" with "FF111111"
    And I fill field "date_piece" with "27/11/2012"
    And I fill field "numero_piece" with "111111"
    And I select "USD" from "Devise *"
    And I fill field "montant_HT_en_devise" with "500,00"
    And I fill field "taux_de_TVA" with "1,999"
    And I fill field "HT" with "500,00"
    And I fill field "commentaires" with "Commentaires"
    And I press "ajax-btn_create" button
    Then I should not see "L'élément a été mis à jour avec succès."