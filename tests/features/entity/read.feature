Feature: View cloudhook entities
  In order to see any links to the cloudhooks collection I need to have the "administer cloudhooks" permission.
  In order to view the cloudhooks collection I need to have the "administer cloudhooks" permission.

  @api
  Scenario: An authenticated user views the configuration menu without the "administer cloudhooks" permission.
    Given I am logged in as a user with the "administer site configuration,access administration pages" permissions
    And I go to "/admin/config"
    Then I should not see the link "Cloudhooks" in the "content" region

  @api
  Scenario: An authenticated user views the system configuration menu without the "administer cloudhooks" permission.
    Given I am logged in as a user with the "administer site configuration,access administration pages" permissions
    And I go to "/admin/config/system"
    Then I should not see the link "Cloudhooks" in the "content" region

  @api
  Scenario: An authenticated user views the configuration menu with the "administer cloudhooks" permission.
    Given I am logged in as a user with the "administer site configuration,access administration pages,administer cloudhooks" permissions
    And I go to "/admin/config"
    Then I should see the link "Cloudhooks" in the "content" region

  @api
  Scenario: View the system configuration menu as a user with the "administer cloudhooks" permission.
    Given I am logged in as a user with the "administer site configuration,access administration pages,administer cloudhooks" permissions
    And I go to "/admin/config/system"
    Then I should see the link "Cloudhooks" in the "content" region

  @api
  Scenario: An anonymous user tries to view the cloudhooks collection
    Given I am on "/admin/config/cloudhooks"
    Then I should get a "403" HTTP response
    And I should see "You are not authorized to access this page."

  @api
  Scenario: An authenticated user tries to view the cloudhooks collection without the "administer cloudhooks" permission
    Given I am logged in as a user with the "administer site configuration,access administration pages" permissions
    And I am on "/admin/config/cloudhooks"
    Then I should get a "403" HTTP response
    And I should see "You are not authorized to access this page."

  @api
  Scenario: An authenticated user tries to view the cloudhooks collection with the "administer cloudhooks" permission
    Given I am logged in as a user with the "administer site configuration,access administration pages,administer cloudhooks" permissions
    And I am on "/admin/config/cloudhooks"
    Then I should get a "200" HTTP response
    And I should see "Cloudhooks configuration"
