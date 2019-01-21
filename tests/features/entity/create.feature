Feature: Create new cloudhook entities
  In order to create new cloudhooks I need to have the "administer cloudhooks" permission.

  @api
  Scenario: An anonymous user tries to create a cloudhook.
    Given I am an anonymous user
    And I go to "/admin/config/cloudhooks/add"
    Then I should get a "403" HTTP response
    And I should see "You are not authorized to access this page."

  @api
  Scenario: An authenticated user without the "administer cloudhooks" permission tries to create a cloudhook.
    Given I am logged in as a user with the "administer site configuration" permission
    And I go to "/admin/config/cloudhooks/add"
    Then I should get a "403" HTTP response
    And I should see "You are not authorized to access this page."

  @api
  Scenario: An authenticated user with the "administer cloudhooks" permission tries to create a cloudhook.
    Given I am logged in as a user with the "administer cloudhooks" permission
    And I go to "/admin/config/cloudhooks"
    Then I should get a "200" HTTP response
    And I click "Add cloudhook"
    Then I should get a "200" HTTP response
    And I should see "Add cloudhook configuration"
    Then I fill in "Label" with "Test cloudhook"
    Then I fill in "Machine-readable name" with "test_cloudhook"
    And I select "Post code deploy" from "Event"
    And I select "Clears the Drush cache" from "Cloudhook plugin"
    And I select "0" from "Weight"
    And I press "Save"
    Then I should get a "200" HTTP response
    And I should see "Saved the Test cloudhook configuration."
